<?php
require __DIR__ . "/../vendor/autoload.php";

use React\Socket\ConnectionInterface;

class ConnectionPool
{
    protected $connections;

    public function __construct()
    {
        $this->connections = new \SplObjectStorage();
    }

    /**
     * @param ConnectionInterface $sender
     */
    public function add(ConnectionInterface $connection)
    {
        $connection->write("Welcome to the chat room\n");
        $connection->write("Enter your name: ");
        $this->connections->attach($connection);
        echo $this->connections->count() . " user(s) online.\n";
        $this->setConnectionName($connection, '');
        $this->initEvents($connection);
    }

    private function initEvents(ConnectionInterface $connection)
    {
        $connection->on('data', function ($data) use ($connection) {
            $name = $this->getConnectionName($connection);

            if (empty($name)) {
                $this->addNewMember($connection, $data);
                return;
            }

            $this->sendToRoom("$name: $data", $connection);
        });

        $connection->on('close', function () use ($connection) {
            $name = $this->getConnectionName($connection);
            $this->connections->offsetUnset($connection);
            $this->sendToRoom("user $name leaves the chat\n", $connection);
            echo $this->connections->count() . " user(s) online.\n";

        });
    }

    private function getConnectionName(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    private function setConnectionName(ConnectionInterface $connection, $name)
    {
        $this->connections->offsetSet($connection, $name);
    }

    private function addNewMember(ConnectionInterface $connection, $name)
    {
        $name = str_replace(["\n", "\r"], "", $name);
        $this->setConnectionName($connection, $name);
        $this->sendToRoom("user $name joins the chat\n", $connection);
    }

    /**
     * @param string $message
     * @param ConnectionInterface $sender
     */
    private function sendToRoom($message, ConnectionInterface $sender)
    {
        foreach ($this->connections as $connection) {
            if ($connection != $sender) {
                $connection->write($message);
            }
        }
    }
}
