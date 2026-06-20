import { Author } from "./author.entity";
import { Subject } from "./subject.entity";

export type Book = {
    CodL: number
    Titulo: string
    Editora: string
    Edicao: number
    AnoPublicacao: number
    Preco: number
    autores?: Author[]
    assuntos?: Subject[]
}