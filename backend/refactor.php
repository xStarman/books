<?php

$domains = [
    'Authors' => [
        'Controllers' => ['StoreAuthorController', 'DeleteAuthorController', 'GetAllAuthorsController', 'GetAuthorByIdController', 'ListAuthorsController', 'UpdateAuthorController'],
        'Services' => ['SaveAuthorService', 'DeleteAuthorService', 'GetAllAuthorsService', 'GetAuthorByIdService', 'GetAuthorListService'],
        'Repositories' => ['AuthorRepository'],
        'Requests' => ['SaveAuthorRequest', 'ListAuthorsRequest'],
        'DTOs' => ['AuthorFiltersDTO']
    ],
    'Subjects' => [
        'Controllers' => ['StoreSubjectController', 'DeleteSubjectController', 'GetAllSubjectsController', 'GetSubjectByIdController', 'ListSubjectsController', 'UpdateSubjectController'],
        'Services' => ['SaveSubjectService', 'DeleteSubjectService', 'GetAllSubjectsService', 'GetSubjectByIdService', 'GetSubjectListService'],
        'Repositories' => ['SubjectRepository'],
        'Requests' => ['SaveSubjectRequest', 'ListSubjectsRequest'],
        'DTOs' => ['SubjectFiltersDTO']
    ],
    'Books' => [
        'Controllers' => ['StoreBookController', 'DeleteBookController', 'GetBookByIdController', 'ListBooksController', 'UpdateBookController'],
        'Services' => ['SaveBookService', 'DeleteBookService', 'GetBookByIdService', 'GetBookListService'],
        'Repositories' => ['BookRepository'],
        'Requests' => ['SaveBookRequest', 'ListBooksRequest'],
        'DTOs' => ['BookFiltersDTO']
    ],
    'Reports' => [
        'Controllers' => ['ReportBookController', 'ReportAuditController'],
        'Services' => ['GetBookReportService', 'GetAuditReportService'],
        'Repositories' => ['BookReportRepository', 'AuditReportRepository'],
        'Requests' => ['ReportBookRequest', 'ReportAuditRequest'],
        'DTOs' => ['BookReportFilterDTO', 'AuditReportFilterDTO'],
        'Exports' => ['BooksExport', 'AuditExport']
    ]
];

$paths = [
    'Controllers' => 'app/Http/Controllers',
    'Services' => 'app/Services',
    'Repositories' => 'app/Repositories',
    'Requests' => 'app/Http/Requests',
    'DTOs' => 'app/DTOs',
    'Exports' => 'app/Exports'
];

$replacements = [];

foreach ($domains as $domain => $types) {
    foreach ($types as $type => $classes) {
        $baseDir = $paths[$type];
        if (!is_dir("$baseDir/$domain")) {
            mkdir("$baseDir/$domain", 0777, true);
        }
        foreach ($classes as $class) {
            $oldPath = "$baseDir/$class.php";
            $newPath = "$baseDir/$domain/$class.php";
            
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);
                
                // Track namespace replacement
                $baseNamespace = str_replace('/', '\\', ucfirst($baseDir));
                $oldNamespace = $baseNamespace;
                $newNamespace = "$baseNamespace\\$domain";
                
                $replacements[$class] = [
                    'oldFqcn' => "$oldNamespace\\$class",
                    'newFqcn' => "$newNamespace\\$class",
                    'oldNs' => $oldNamespace,
                    'newNs' => $newNamespace,
                    'newPath' => $newPath
                ];
            }
        }
    }
}

// Recursively update all PHP files in the backend
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app'));
$files = [];
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $files[] = $file->getPathname();
    }
}
$files[] = 'routes/api.php';

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // First update the namespace declaration if the file itself was moved
    foreach ($replacements as $class => $r) {
        if ($file === $r['newPath']) {
            $content = preg_replace(
                "/^namespace\s+" . preg_quote($r['oldNs'], '/') . "\s*;/m",
                "namespace " . $r['newNs'] . ";",
                $content
            );
        }
    }
    
    // Now update use statements for all classes
    foreach ($replacements as $class => $r) {
        // Update uses like: use App\Http\Controllers\StoreBookController;
        $content = preg_replace(
            "/use\s+" . preg_quote($r['oldFqcn'], '/') . "\s*;/m",
            "use " . $r['newFqcn'] . ";",
            $content
        );
        // Sometimes it's aliased or part of a group, but standard Laravel doesn't do group uses often.
        // Update usages in inline phpdoc blocks if any
        $content = str_replace($r['oldFqcn'], $r['newFqcn'], $content);
    }
    
    if ($content !== $original) {
        file_put_contents($file, $content);
    }
}

echo "Refactoring completed.\n";

