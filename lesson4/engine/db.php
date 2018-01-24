<?php
class Connection {
    // подключение к базе
    public static function ConnectToDB(){
        try {
            return new PDO('mysql:host=' . DB_HOST . '; dbname=' . DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

//Работа с базой

class DbQuery {
    private $db;

    public function __construct(PDO $pdo){
        $this->db = $pdo;
    }

    //Получаем все картинки из базы
    public function getAllImages(){
        try {
            $sth = $this->db->prepare('SELECT * FROM ' . IMG_TABLE . ' ORDER BY count DESC');
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //Записываем в базу инфу о загруженной картинке
    public function insertImage($file, $name=NULL, $title=NULL){
        $filepath=realpath($file); //Получаю полный адрес файла в файловой системе
        if (file_exists($filepath)) {  // Есть ли файл на сервере ?
            $attr=getimagesize($file);  // Возвращает информацию о файле
            if ($attr) {
                $type=$attr['mime']; // Mime Type
                $w=$attr[0]; // Width
                $h=$attr[1]; // Height
                $size=filesize( $filepath ); // File size
            
                try {
                    $sth = $this->db->prepare("INSERT INTO " . IMG_TABLE . " (file, size, w, h, name, type, title) VALUES ('$file', '$size', '$w', '$h', '$name', '$type', '$title')");
                    $sth->execute();
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return FALSE;
                }
            }
            else {
                return FALSE;
                echo "Загружена какая-то шняга а не картинка!";
            }
        }
        else {
            return FALSE;
            echo "Загружаемого файла не существует!";
        }
    }

    //Получаем из базы одну картинку и обновляем счётчик
    public function getImage($id) {
        try {
            $sth = $this->db->prepare("UPDATE " . IMG_TABLE . " SET count=count+1 WHERE id=$id;"); // Обновление счётчика
            $sth->execute();
            $sth = $this->db->prepare("SELECT * FROM " . IMG_TABLE . " WHERE id=$id;"); // Вывод элемента
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //Очищает таблицу с изображениями
    public function clearImages() {
        try {
            $sth = $this->db->prepare("TRUNCATE TABLE " . IMG_TABLE);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //Заполняет базу на основе файлов в папке IMG_DIR
    public function create_base () {
        $file_parts = array();
        $ext="";
        $title="";
        
        $dir_handle = @opendir(IMG_DIR) or die("Ошибка при открытии папки !!!");

        while ($file = readdir($dir_handle))  
        {
            if($file=="." || $file == "..") continue;  
            
            $file_parts = explode(".",$file);
            $ext = strtolower(array_pop($file_parts));
    
            
            if(in_array($ext,IMG_TYPES)){
                $this->insertImage(IMG_DIR . $file);
            } 
        
        }
    
        closedir($dir_handle);  //закрыть папку
    }
}

$db = new DbQuery(Connection::ConnectToDB()); //Подключение к базе данных

//Функция первоначального заполнения базы
if (DB_INIT === TRUE ) { // Пересоздаём таблицу с изображениями
    $db->clearImages(); // Очищает таблицу с изображениями
    $db->create_base(); // Первоначальное заполнение базы
}

//var_dump( $db->getImage(1));