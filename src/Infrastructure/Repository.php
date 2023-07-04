<?php

namespace Infrastructure;
use Application\Entities\Product;
use Application\Entities\Rating;

class Repository
implements
    \Application\Interfaces\ProductRepository,
    \Application\Interfaces\UserRepository,
    \Application\Interfaces\RatingRepository
{
    private $server;
    private $userName;
    private $password;
    private $database;

    public function __construct(string $server, string $userName, string $password, string $database)
    {
        $this->server = $server;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;
    }

    // === private helper methods ===

    private function getConnection()
    {
        $con = new \mysqli($this->server, $this->userName, $this->password, $this->database);
        if (!$con) {
            die('Unable to connect to database. Error: ' . mysqli_connect_error());
        }
        return $con;
    }
    private function executeQuery($connection, $query)
    {
        $result = $connection->query($query);
        if (!$result) {
            die("Error in query '$query': " . $connection->error);
        }
        return $result;
    }

    private function executeStatement($connection, $query, $bindFunc)
    {
        $statement = $connection->prepare($query);
        if (!$statement) {
            die("Error in prepared statement '$query': " . $connection->error);
        }
        $bindFunc($statement);
        if (!$statement->execute()) {
            die("Error executing prepared statement '$query': " . $statement->error);
        }
        return $statement;
    }

    public function getUser(int $id): ?\Application\Entities\User
    {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT id, username FROM users WHERE id = ?',
            function ($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );
        $stat->bind_result($id, $userName);
        if ($stat->fetch()) {
            $user = new \Application\Entities\User($id, $userName);
        }
        $stat->close();
        $con->close();

        return $user;
    }

    public function getUserForUserNameAndPassword(string $userName, string $password): ?\Application\Entities\User
    {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT id, passwordHash FROM users WHERE username = ?',
            function ($s) use ($userName) {
                $s->bind_param('s', $userName);
            }
        );
        $stat->bind_result($id, $passwordHash);
        if ($stat->fetch() && password_verify($password, $passwordHash)) {
            $user = new \Application\Entities\User($id, $userName);
        }
        $stat->close();
        $con->close();

        return $user;
    }
    public function getUserForUsername(string $username): ?\Application\Entities\User
    {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT id, passwordHash FROM users WHERE userName = ?',
            function ($s) use ($username) {
                $s->bind_param('s', $username);
            }
        );
        $stat->bind_result($id, $passwordHash);
        if ($stat->fetch()) {
            $user = new \Application\Entities\User($id, $username, $passwordHash);
        }
        $stat->close();
        $con->close();
        return $user;
    }
    public function getProduct(int $id): ?Product
    {
        $product = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT id, name, price, User_id, producer FROM `Product` WHERE id = ?',
            function($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );

        $stat->bind_result($id, $name, $price, $User_id, $producer);
        if ($stat->fetch()) {
            $product = new \Application\Entities\Product(
                $id,
                $name,
                $price,
                $producer,
                $User_id

            );
        }

        $stat->close();
        $con->close();

        return $product;
    }
    public function getProducts(): array
    {
        $products = [];
        $con = $this->getConnection();
        $result = $this->executeQuery(
            $con,
            'SELECT id, name, price, User_id, producer FROM Product'
        );
        while ($product = $result->fetch_object()) {
            $products[] =
                new \Application\Entities\Product(
                    $product->id,
                    $product->name,
                    $product->price,
                    $product->producer,
                    $product->User_id
                );
        }
        $result->close();
        $con->close();
        return $products;
    }
    public function getProductForFilter(string $filter): array
    {

        $products = [];
        $filter = "%$filter%";
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT id, name, User_id, price, producer FROM Product  WHERE (name LIKE ? || producer LIKE ?)',
            function ($s) use ($filter) {
                $s->bind_param('ss', $filter, $filter);
            }
        );

        $stat->bind_result($id, $name, $User_id, $price, $producer);
        while ($stat->fetch()) {

            $products[] = new \Application\Entities\Product(
                    $id,
                    $name,
                    $price,
                    $producer,
                    $User_id
                 );
        }
        $stat->close();
        $con->close();

        return $products;
    }

    public function getAverageRatingForProduct(int $productId): float
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT avg(Rating) as avgRat FROM `Rating` WHERE idProduct = ?',
            function($s) use ($productId) {
                $s->bind_param('i', $productId);
            }
        );
        $stat->bind_result($avgRat);
        $stat->fetch();
        $stat->close();
        $con->close();
        //echo $avgRat;
        return round($avgRat, 2);
    }

    public function getTotalAmountOfRatingsForProduct(int $id): int
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT count(*) as c FROM `rating` WHERE idProduct = ?',
            function($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );

        $stat->bind_result($c);
        $stat->fetch();
        $stat->close();
        $con->close();
        return $c ;

    }


    public function createUser(string $userName, string $password): ?int
    {

        $con = $this->getConnection();
        $con->autocommit(false);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stat = $this->executeStatement(
            $con,
            'INSERT INTO users (userName, passwordHash) VALUES (?, ?)',
            function ($s) use ($userName, $password) {
                $s->bind_param('ss', $userName, $password);
            }
        );

        $value = $stat;
        $stat->close();
        $con->commit();
        $con->close();
        if($value){
            return 1;
        }
        return null;
    }


    /**
     * Check if user can edit Product
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function canEdit(int $id, int $userId): bool
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT COUNT(id) as count FROM product WHERE id = ? && User_id = ?',
            function($s) use ($id, $userId) {
                $s->bind_param('ii', $id, $userId);
            }
        );
        $stat->bind_result($count);
        $stat->fetch();
        $stat->close();
        $con->close();





        return $count >= 1;

    }


    /**
     * Edit Product
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function edit(int $id, string $producer, string $name)
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'UPDATE Product SET name = ?, producer = ? WHERE id = ?',
            function($s) use ($producer, $name, $id) {
                $s->bind_param('ssi',  $name, $producer, $id);
            }
        );
        $stat->close();
        $con->close();
    }

    public function getRatingsFromProduct(int $productId): array
    {
        $ratings = [];
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'SELECT idRating, idProduct, rating, comment, User_id FROM `Rating` WHERE idProduct = ? ORDER BY date DESC',
            function($s) use ($productId) {
                $s->bind_param('i', $productId);
            }
        );

        $stat->bind_result($idRating, $idProduct, $rating, $comment, $User_id);
        while ($stat->fetch()) {
            $ratings[] = new Rating($idRating,   $rating,  $comment, $idProduct, $User_id);
        }

        $stat->close();
        $con->close();

        return $ratings;
    }

    public function addRatingToDatabase(string $productId, int $idUser, int $rating, string $comment)
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'INSERT INTO `Rating` (`id`, `productId`, `rating`, `comment`, `date`) VALUES (?, ?, ?, ?, NOW())',
            function($s) use ($userId, $productId, $rating, $comment) {
                $s->bind_param('iiis', $userId, $productId, $rating, $comment);
            }
        );

        $value = $stat;
        $stat->close();
        $con->close();

        if($value){
            return 1;
        }
        return null;
    }

    public function removeRatingFromDatabase(int $id)
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'DELETE FROM Rating WHERE idRating = ?',
            function($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );
        $stat->close();
        $con->close();
        return null;
    }

    public function addProductToDatabase($manufacturer, $userId, $name, $price) : ?int
    {
        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            'INSERT INTO product (name, price,  User_id,  producer) VALUES (?, ?, ?, ?);',
            function($s) use ( $name, $price, $userId, $manufacturer) {
                $s->bind_param('ssis', $name, $price, $userId, $manufacturer);
            }
        );

        $value = $stat;
        $stat->close();
        $con->close();

        if($value != null){
            return 1;
        }
        return null;
    }
}
 