<?php
/*ogolne repozytorium i pozniej konkretne dla kazdego obiektu np userRepositpry, DreamRepository
i tam metoda pobierania danych z fetchem. foreach mozna uproscic zeby od razu mapowalo do obiektow
obslugi bledow nie ma w kodie z prezentacji, sprawdzic czy fetchall nie zwroci false. 
trzeba pamietac o zamknieciu polaczenia
nie mam pojecia co sie dzieje :)
z fetch_class powinno sie samo zainicjalizuje
przy duzej ilosci zapytan trzeba uzyc transakcji bo to sie wypierdoli na rowerku
zapewnia to ze wszystkie zapytania beda wykonane albo zadna, tak jak na bazach 
*/

require_once "/app/autoloader.php";

class Repository {
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }
}