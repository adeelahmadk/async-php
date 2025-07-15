<?php
namespace AdeelAhmadK\Phpchat\Server;

require __DIR__ . "/../../vendor/autoload.php";

use Colors\Color;
use React\Socket\ConnectionInterface;

class ConnectionPool
{
    protected $connections;

    /**
     * Initializes a new ConnectionPool instance.
     *
     * This constructor sets up an internal storage for connections
     * using SplObjectStorage as a mp, with connection objects as keys
     * and names as values.
     */
    public function __construct()
    {
        $this->connections = new \SplObjectStorage();
    }

    /**
     * Add a new connection to the pool.
     *
     * @param ConnectionInterface $sender
     */
    public function add(ConnectionInterface $connection)
    {
        $connection->write(
            (new Color("Welcome to the chat room\n"))->fg('green')
        );
        $connection->write("Enter your name: ");
        $this->connections->attach($connection);
        echo $this->connections->count() . " user(s) online.\n";
        $this->setConnectionName($connection, '');
        $this->initEvents($connection);
    }

    /**
     * Initiate the events for a given connection.
     *
     * Attache event listeners to the connection:
     * - `data` event: sends a message to all the users in the room.
     * - `close` event: removes the connection from the pool, and notifies all
     *   the users in the room.
     *
     * @param ConnectionInterface $connection The connection to initiate the events for.
     */
    private function initEvents(ConnectionInterface $connection)
    {
        // Handle incoming messages
        $connection->on('data', function ($data) use ($connection) {
            $name = $this->getConnectionName($connection);

            if (empty($name)) {
                $this->addNewMember($connection, $data);
                return;
            }

            $this->sendToRoom(
                (new Color("$name:"))->bold() . " $data",
                $connection
            );
        });

        // Handle disconnections
        $connection->on('close', function () use ($connection) {
            $name = $this->getConnectionName($connection);
            $this->connections->offsetUnset($connection);
            $this->sendToRoom(
                (new Color("user $name leaves the chat\n"))->fg('red'),
                $connection
            );
            echo $this->connections->count() . " user(s) online.\n";

        });
    }

    /**
     * Get the name associated with a connection.
     *
     * @param ConnectionInterface $connection The connection to get the name for.
     * @return string The user's name associated with the connection.
     */
    private function getConnectionName(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    /**
     * Associate name to a connection.
     *
     * @param ConnectionInterface $connection The connection for the user.
     * @param string $name Name of the user.
     */
    private function setConnectionName(ConnectionInterface $connection, $name)
    {
        $this->connections->offsetSet($connection, $name);
    }

    /**
     * Add a new member to the chat room by setting their name and notify
     * all other users.
     *
     * @param ConnectionInterface $connection The connection for the new member.
     * @param string $name Name of the new member.
     */
    private function addNewMember(ConnectionInterface $connection, string $name)
    {
        $name = str_replace(["\n", "\r"], "", $name);
        $this->setConnectionName($connection, $name);
        $this->sendToRoom(
            (new Color("user $name joins the chat\n"))->fg('blue'),
            $connection
        );
    }

    /**
     * Send a message to all users in the chat room.
     *
     * @param string $message
     * @param ConnectionInterface $sender
     */
    private function sendToRoom(string $message, ConnectionInterface $sender)
    {
        foreach ($this->connections as $connection) {
            if ($connection != $sender) {
                $connection->write($message);
            }
        }
    }
}
