<?php

namespace Modules\ModuleManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Modules\ModuleManagement\Models\ModuleManagement;
use ZipArchive;

class ModuleManagementController extends Controller
{
    public function index()
    {
        $modules = ModuleManagement::all();
        return view('modulemanagement::index', compact('modules'));
    }

    public function create()
    {
        return view('modulemanagement::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z0-9]+$/',
            'description' => 'nullable|string',
            'enabled' => 'required|boolean',
        ]);

        $moduleName = $validated['name'];
        $modulePath = base_path('Modules/' . $moduleName);

        if (File::exists($modulePath)) {
            return back()->withErrors(['name' => 'Module already exists.']);
        }

        // Create the module and its migration
        Artisan::call('module:make', ['name' => [$moduleName]]);
        Artisan::call('module:make-migration', [
            'name' => 'create_' . strtolower($moduleName) . '_table',
            'module' => $moduleName
        ]);

        // Rename Database and subfolders to lowercase
        $databasePath = $modulePath . '/Database';
        if (File::exists($databasePath)) {
            $newDatabasePath = $modulePath . '/database';
            File::move($databasePath, $newDatabasePath);

            $migrationsPath = $newDatabasePath . '/Migrations';
            if (File::exists($migrationsPath)) {
                File::move($migrationsPath, $newDatabasePath . '/migrations');
            }

            $factoriesPath = $newDatabasePath . '/Factories';
            if (File::exists($factoriesPath)) {
                File::move($factoriesPath, $newDatabasePath . '/factories');
            }
            $seedersPath = $newDatabasePath . '/Seeders';
            if (File::exists($seedersPath)) {
                File::move($seedersPath, $newDatabasePath . '/seeders');
            }

            // Update references in generated files
            $providerPath = $modulePath . '/Providers/' . $moduleName . 'ServiceProvider.php';
            if (File::exists($providerPath)) {
                $content = File::get($providerPath);
                File::put($providerPath, str_replace('Database/Migrations', 'database/migrations', $content));
            }

            $composerJsonPath = $modulePath . '/composer.json';
            if (File::exists($composerJsonPath)) {
                $content = File::get($composerJsonPath);
                $content = str_replace('Database/Factories', 'database/factories', $content);
                $content = str_replace('Database/Seeders', 'database/seeders', $content);
                File::put($composerJsonPath, $content);
            }
        }

        // Move everything from app to root and remove app directory
        $appPath = $modulePath . '/app';
        if (File::exists($appPath) && File::isDirectory($appPath)) {
            // Move all contents (files and directories) from app to root
            File::copyDirectory($appPath, $modulePath);
            // Delete the app directory and its contents
            File::deleteDirectory($appPath);
        }

        // Create Models directory and move the existing model file
        $modelsPath = $modulePath . '/Models';
        File::makeDirectory($modelsPath, 0755, true);
        
        $modelFile = $modulePath . '/' . $moduleName . '.php';
        if (File::exists($modelFile)) {
            File::move($modelFile, $modelsPath . '/' . $moduleName . '.php');
        }

        // Update module.json
        $moduleJsonPath = $modulePath . '/module.json';
        if (File::exists($moduleJsonPath)) {
            $moduleJson = json_decode(File::get($moduleJsonPath), true);
            $moduleJson['description'] = $validated['description'];
            $moduleJson['enabled'] = $validated['enabled'];
            File::put($moduleJsonPath, json_encode($moduleJson, JSON_PRETTY_PRINT));
        }

        ModuleManagement::create($validated);

        return redirect()->route('modulemanagement.index')->with('success', 'Module created successfully.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip',
        ]);

        $file = $request->file('file');
        $tempPath = storage_path('app/temp/' . time());
        File::makeDirectory($tempPath, 0755, true);

        $zip = new ZipArchive;
        if ($zip->open($file->getRealPath()) !== true) {
            File::deleteDirectory($tempPath);
            return back()->withErrors(['file' => 'Invalid zip file.']);
        }

        $zip->extractTo($tempPath);
        $zip->close();

        $directories = File::directories($tempPath);
        if (empty($directories)) {
            File::deleteDirectory($tempPath);
            return back()->withErrors(['file' => 'Zip must contain a module directory.']);
        }

        $moduleName = basename($directories[0]);
        $extractedModulePath = $directories[0];
        $modulePath = base_path('Modules/' . $moduleName);

        if (File::exists($modulePath)) {
            File::deleteDirectory($modulePath);
        }

        File::moveDirectory($extractedModulePath, $modulePath);

        // Move contents from app to root and remove app directory
        $appPath = $modulePath . '/app';
        if (File::exists($appPath) && File::isDirectory($appPath)) {
            $directories = File::directories($appPath);
            if (is_array($directories) && !empty($directories)) {
                foreach ($directories as $dir) {
                    $dirName = basename($dir);
                    File::moveDirectory($dir, $modulePath . '/' . $dirName);
                }
            }
            File::deleteDirectory($appPath);
        }

        File::deleteDirectory($tempPath);

        $module = ModuleManagement::firstOrNew(['name' => $moduleName]);
        $module->description = '';
        $module->enabled = true;
        $module->save();

        return redirect()->route('modulemanagement.index')->with('success', 'Module updated successfully.');
    }

    public function edit(ModuleManagement $modulemanagement)
    {
        return view('modulemanagement::edit', compact('modulemanagement'));
    }

    public function update(Request $request, ModuleManagement $modulemanagement)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'enabled' => 'required|boolean',
        ]);

        $modulemanagement->update($validated);

        $moduleJsonPath = base_path('Modules/' . $modulemanagement->name . '/module.json');
        if (File::exists($moduleJsonPath)) {
            $moduleJson = json_decode(File::get($moduleJsonPath), true);
            $moduleJson['description'] = $validated['description'];
            $moduleJson['enabled'] = (bool)$validated['enabled'];
            File::put($moduleJsonPath, json_encode($moduleJson, JSON_PRETTY_PRINT));
        }

        return redirect()->route('modulemanagement.index')->with('success', 'Module updated successfully.');
    }

    public function destroy(ModuleManagement $modulemanagement)
    {
        $moduleName = $modulemanagement->name;
        $modulePath = base_path('Modules/' . $moduleName);

        if (File::exists($modulePath)) {
            File::deleteDirectory($modulePath);
        }

        $modulemanagement->delete();

        // Remove from modules_statuses.json
        $statusesFile = base_path('modules_statuses.json');
        if (File::exists($statusesFile)) {
            $statuses = json_decode(File::get($statusesFile), true);
            if (isset($statuses[$moduleName])) {
                unset($statuses[$moduleName]);
                File::put($statusesFile, json_encode($statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        }

        return redirect()->route('modulemanagement.index')->with('success', 'Module deleted successfully.');
    }
}
