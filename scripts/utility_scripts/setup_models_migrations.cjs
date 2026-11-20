const fs = require('fs');
const path = require('path');

const basePath = 'e:/magang/tsth2-redesign';

// Models
const models = {
    'AboutBullet.php': `<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass AboutBullet extends Model\n{\n    protected $fillable = ['content'];\n}\n`,
    'Program.php': `<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Program extends Model\n{\n    protected $fillable = ['icon', 'title', 'description'];\n}\n`,
    'Research.php': `<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Research extends Model\n{\n    protected $table = 'research';\n    protected $fillable = ['icon', 'title', 'description', 'image_path'];\n}\n`,
    'News.php': `<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass News extends Model\n{\n    protected $fillable = ['category', 'title', 'description', 'date', 'image_path'];\n}\n`,
    'Gallery.php': `<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Gallery extends Model\n{\n    protected $fillable = ['title', 'description', 'image_path'];\n}\n`
};

for (const [file, content] of Object.entries(models)) {
    fs.writeFileSync(path.join(basePath, 'app', 'Models', file), content);
}

// Migrations
const migrationDir = path.join(basePath, 'database', 'migrations');
const files = fs.readdirSync(migrationDir);

files.forEach(file => {
    let content = fs.readFileSync(path.join(migrationDir, file), 'utf8');
    let changed = false;

    if (file.includes('create_about_bullets_table')) {
        content = content.replace('$table->id();', "$table->id();\n            $table->string('content');");
        changed = true;
    } else if (file.includes('create_programs_table')) {
        content = content.replace('$table->id();', "$table->id();\n            $table->string('icon')->nullable();\n            $table->string('title');\n            $table->text('description');");
        changed = true;
    } else if (file.includes('create_research_table')) {
        content = content.replace('$table->id();', "$table->id();\n            $table->string('icon')->nullable();\n            $table->string('title');\n            $table->text('description');\n            $table->string('image_path')->nullable();");
        changed = true;
    } else if (file.includes('create_news_table')) {
        content = content.replace('$table->id();', "$table->id();\n            $table->string('category');\n            $table->string('title');\n            $table->text('description');\n            $table->string('date')->nullable();\n            $table->string('image_path')->nullable();");
        changed = true;
    } else if (file.includes('create_galleries_table')) {
        content = content.replace('$table->id();', "$table->id();\n            $table->string('title');\n            $table->text('description')->nullable();\n            $table->string('image_path');");
        changed = true;
    }

    if (changed) {
        fs.writeFileSync(path.join(migrationDir, file), content);
    }
});

console.log('Models and Migrations updated.');
