const fs = require('fs');

if (process.argv.length > 2) {
    const file = process.argv[2];
    if (fs.existsSync(file)) {
        eval(fs.readFileSync(file,'utf8'));
    }
}

process.stdout.write(JSON.stringify(gamedata || null));
