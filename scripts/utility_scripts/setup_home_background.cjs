const fs = require('fs');
const path = require('path');

const basePath = 'e:/magang/tsth2-redesign';

// 1. Update HomeBackground Model
const modelPath = path.join(basePath, 'app', 'Models', 'HomeBackground.php');
const modelContent = `<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class HomeBackground extends Model
{
    protected $fillable = ['image_path'];
}
`;
fs.writeFileSync(modelPath, modelContent);

// 2. Update Migrations
const migrationDir = path.join(basePath, 'database', 'migrations');
const files = fs.readdirSync(migrationDir);

files.forEach(file => {
    let content = fs.readFileSync(path.join(migrationDir, file), 'utf8');
    let changed = false;

    if (file.includes('create_home_backgrounds_table')) {
        content = content.replace('$table->id();', "$table->id();\n            $table->string('image_path');");
        changed = true;
    } else if (file.includes('remove_icon_from_programs_and_research')) {
        content = `<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('programs', 'icon')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
        
        if (Schema::hasColumn('research', 'icon')) {
            Schema::table('research', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        Schema::table('research', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
    }
};
`;
        changed = true;
    }

    if (changed) {
        fs.writeFileSync(path.join(migrationDir, file), content);
    }
});

console.log('Model and Migrations updated.');
