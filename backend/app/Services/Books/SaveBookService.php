<?php

namespace App\Services\Books;

use App\Exceptions\BookAlreadyExistsException;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use App\Exceptions\AuthorAlreadyExistsException;
use App\Exceptions\SubjectAlreadyExistsException;
use Illuminate\Support\Facades\DB;
use App\Repositories\Authors\AuthorRepository;
use App\Repositories\Subjects\SubjectRepository;

class SaveBookService
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private SubjectRepository $subjectRepository
    ) {}

    public function execute(array $data, ?int $bookId = null): Livro
    {
        return DB::transaction(function () use ($data, $bookId) {
            $existingQuery = Livro::where('Titulo', $data['Titulo'])
                ->where('Editora', $data['Editora'])
                ->where('Edicao', $data['Edicao'] ?? 1)
                ->where('AnoPublicacao', $data['AnoPublicacao']);

            if ($bookId) {
                $existingQuery->where('CodL', '!=', $bookId);
            }

            if ($existingQuery->lockForUpdate()->exists()) {
                throw new BookAlreadyExistsException();
            }

            $livro = $bookId ? Livro::findOrFail($bookId) : new Livro();
            
            $livro->Titulo = $data['Titulo'];
            $livro->Editora = $data['Editora'];
            $livro->Edicao = $data['Edicao'] ?? 1;
            $livro->AnoPublicacao = $data['AnoPublicacao'];
            $livro->Preco = $data['Preco'];
            
            $livro->save();

            if (isset($data['autores'])) {
                $autorIds = [];
                foreach ($data['autores'] as $autorId) {
                    if (is_string($autorId) && str_starts_with($autorId, 'novo:')) {
                        $nome = str_replace('novo:', '', $autorId);
                        try {
                            $autor = $this->authorRepository->save(['Nome' => mb_substr($nome, 0, 40)]);
                            $autorIds[] = $autor->CodAu;
                        } catch (AuthorAlreadyExistsException $e) {
                            $autor = Autor::where('Nome', mb_substr($nome, 0, 40))->first();
                            if ($autor) $autorIds[] = $autor->CodAu;
                        }
                        continue;
                    } 
                    
                    $autorIds[] = (int) $autorId;
                }
                $livro->autores()->sync($autorIds);
            }
            if (isset($data['assuntos'])) {
                $assuntoIds = [];
                foreach ($data['assuntos'] as $assuntoId) {
                    if (is_string($assuntoId) && str_starts_with($assuntoId, 'novo:')) {
                        $descricao = str_replace('novo:', '', $assuntoId);
                        try {
                            $assunto = $this->subjectRepository->save(['Descricao' => mb_substr($descricao, 0, 20)]);
                            $assuntoIds[] = $assunto->CodAs;
                        } catch (SubjectAlreadyExistsException $e) {
                            $assunto = Assunto::where('Descricao', mb_substr($descricao, 0, 20))->first();
                            if ($assunto) $assuntoIds[] = $assunto->CodAs;
                        }
                        continue;
                    } 
                    
                    $assuntoIds[] = (int) $assuntoId;
                }
                $livro->assuntos()->sync($assuntoIds);
            }

            return $livro->load(['autores', 'assuntos']);
        });
    }
}
