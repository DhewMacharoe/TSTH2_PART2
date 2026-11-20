const fs = require('fs');
const path = require('path');

const basePath = 'e:/magang/tsth2-redesign';

// 1. AppServiceProvider
const aspPath = path.join(basePath, 'app/Providers/AppServiceProvider.php');
let aspContent = fs.readFileSync(aspPath, 'utf8');

if (!aspContent.includes('use App\\Models\\HomeBackground;')) {
    aspContent = aspContent.replace('use App\\Models\\Content;', "use App\\Models\\Content;\nuse App\\Models\\HomeBackground;");
}
if (!aspContent.includes("with('homeBackgrounds'")) {
    aspContent = aspContent.replace("->with('aboutBullets', AboutBullet::all())", "->with('homeBackgrounds', HomeBackground::all())\n                 ->with('aboutBullets', AboutBullet::all())");
}
fs.writeFileSync(aspPath, aspContent);

// 2. WebController
const wcPath = path.join(basePath, 'app/Http/Controllers/WebController.php');
let wcContent = fs.readFileSync(wcPath, 'utf8');

if (!wcContent.includes('use App\\Models\\HomeBackground;')) {
    wcContent = wcContent.replace('use App\\Models\\Content;', "use App\\Models\\Content;\nuse App\\Models\\HomeBackground;");
}

// In home(), add $homeBackgrounds
if (!wcContent.includes('$homeBackgrounds = HomeBackground::all();')) {
    wcContent = wcContent.replace("$aboutBullets = AboutBullet::all();", "$aboutBullets = AboutBullet::all();\n        $homeBackgrounds = HomeBackground::all();");
    wcContent = wcContent.replace("compact('contents', 'aboutBullets')", "compact('contents', 'aboutBullets', 'homeBackgrounds')");
}

// Update Program store method (remove icon)
wcContent = wcContent.replace(/Program::create\(\$request->validate\(\['icon' => 'nullable', 'title' => 'required', 'description' => 'required'\]\)\);/g, 
    "Program::create($request->validate(['title' => 'required', 'description' => 'required']));");

// Update Research store method (remove icon, make image required)
// old: $data = $request->validate(['icon' => 'nullable', 'title' => 'required', 'description' => 'required', 'image_path' => 'nullable|image']);
wcContent = wcContent.replace(/\[\'icon\' => \'nullable\', \'title\' => \'required\', \'description\' => \'required\', \'image_path\' => \'nullable\|image\'\]/g, 
    "['title' => 'required', 'description' => 'required', 'image_path' => 'required|image']");

// Add HomeBackground CRUD
if (!wcContent.includes('storeHomeBackground')) {
    const bgCrud = `
    public function storeHomeBackground(Request $request) {
        $data = $request->validate(['image_path' => 'required|image']);
        if ($request->hasFile('image_path')) $data['image_path'] = $request->file('image_path')->store('contents', 'public');
        HomeBackground::create($data);
        return back()->with('success', 'Background ditambahkan!');
    }
    public function destroyHomeBackground($id) {
        HomeBackground::findOrFail($id)->delete();
        return back()->with('success', 'Background dihapus!');
    }
`;
    wcContent = wcContent.replace(/}\s*$/g, bgCrud + "\n}");
}
fs.writeFileSync(wcPath, wcContent);

// 3. routes/web.php
const routesPath = path.join(basePath, 'routes/web.php');
let routesContent = fs.readFileSync(routesPath, 'utf8');

if (!routesContent.includes('storeHomeBackground')) {
    const bgRoutes = `
    Route::post('/web-management/home-backgrounds', [WebController::class, 'storeHomeBackground'])->name('web.management.home_bg.store');
    Route::delete('/web-management/home-backgrounds/{id}', [WebController::class, 'destroyHomeBackground'])->name('web.management.home_bg.destroy');
`;
    routesContent = routesContent.replace("// Dynamic CMS Routes", "// Dynamic CMS Routes" + bgRoutes);
}
fs.writeFileSync(routesPath, routesContent);

console.log('Backend updated.');
