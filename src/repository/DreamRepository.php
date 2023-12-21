<?php
require_once "/app/autoloader.php";
class DreamRepository extends Repository
{    
    public function getMyDreams(): array
    {
        $result = [];

        $stmt = $this->database->connect()->prepare('
            SELECT * FROM dreams where user_id=;
        ');
        $stmt->execute();
        $dreams = $stmt->fetchAll(PDO::FETCH_ASSOC);

         foreach ($dreams as $dream) {
             $result[] = new Dream(
                 $dream['title'],
                 $dream['dream_content'],
                 $dream['date']
             );
         }

        return $result;
    }
}