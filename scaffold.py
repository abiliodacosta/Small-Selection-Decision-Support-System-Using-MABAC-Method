import os

def create_structure():
    dirs = [
        'app/Controllers',
        'app/Models',
        'app/Middleware',
        'app/Services',
        'app/Helpers',
        'config',
        'database/migrations',
        'database/seeders',
        'public/assets/css',
        'public/assets/js',
        'public/assets/images',
        'public/assets/uploads',
        'resources/views/auth',
        'resources/views/dashboard',
        'resources/views/candidates',
        'resources/views/criteria',
        'resources/views/evaluations',
        'resources/views/reports',
        'resources/views/layouts',
        'storage/logs',
        'storage/cache',
        'tests'
    ]

    for d in dirs:
        os.makedirs(os.path.join('c:/xampp/htdocs/app-dss', d), exist_ok=True)
        
    print("Directories created.")

if __name__ == '__main__':
    create_structure()
