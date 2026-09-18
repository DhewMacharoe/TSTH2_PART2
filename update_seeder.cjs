const fs = require('fs');
const path = require('path');

const file = 'e:/magang/tsth2-redesign/database/seeders/ContentSeeder.php';
let content = fs.readFileSync(file, 'utf8');

if (!content.includes('use App\\Models\\HomeBackground;')) {
    content = content.replace('use App\\Models\\Content;', "use App\\Models\\Content;\nuse App\\Models\\HomeBackground;");
}

// Remove static hero bgs
content = content.replace(/.*'key' => 'home_hero_bg_1'.*\n/g, '');
content = content.replace(/.*'key' => 'home_hero_bg_2'.*\n/g, '');

// Remove icons from programs
content = content.replace(/'icon' => '🌱',\n\s*/g, '');
content = content.replace(/'icon' => '🤝',\n\s*/g, '');
content = content.replace(/'icon' => '🎓',\n\s*/g, '');

// Remove icons from research
content = content.replace(/'icon' => '🔬',\n\s*/g, '');
content = content.replace(/'icon' => '🌿',\n\s*/g, '');
content = content.replace(/'icon' => '🏭',\n\s*/g, '');

// Add HomeBackground seeding
if (!content.includes('HomeBackground::truncate();')) {
    const bgSeeding = `
        // Home Backgrounds
        HomeBackground::truncate();
        HomeBackground::create(['image_path' => 'contents/home_hero_bg_1.png']);
        HomeBackground::create(['image_path' => 'contents/home_hero_bg_2.png']);
`;
    content = content.replace('// About Bullets', bgSeeding + '\n        // About Bullets');
}

fs.writeFileSync(file, content);
console.log('Seeder updated.');
