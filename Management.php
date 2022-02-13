<?php

include "./User.php";

class Management
{
    public $queue = [];
    public $output_file;

    function __construct($file)
    {
        $this->output_file = $file;
    }

    // Receives a user to add to the queue, returns the user’s position in the queue
    public function add($userId)
    {
        // Insert the new user in the queue
        $user = new User($userId);
        array_push($this->queue, $user);

        // Set up position
        $user->setPosition($position = array_key_last($this->queue));

        file_put_contents($this->output_file, 'ADD,' . $userId . ' : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        return $position;
    }

    // Receives a user and removes it from the queue
    public function removeByUser($userId)
    {
        //Remove the user from the queue
        if (User::exist($userId)) {
            $pos = User::lookupById($userId)->getPosition();
            array_splice($this->queue, $pos, 1);

            // Remove the user from users
            User::removeUser($userId);

            // Change users' position between start and end by 1
            for ($i = $pos; $i < count($this->queue); $i++) {
                $this->queue[$i]->setPosition($i);
            }

            file_put_contents($this->output_file, 'REMOVE_USER,' . $userId . ' : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($this->output_file, 'REMOVE_USER,' . $userId . ' : NOT SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        }
    }

    // Receives a queue position and removes the user at that position from the queue.
    public function removeByPosition($position)
    {
        if (array_key_exists($position, $this->queue)) {

            // Remove the user from users
            User::removeUser($this->queue[$position]->getId());

            // Remove the user from the position
            array_splice($this->queue, $position, 1);

            file_put_contents($this->output_file, 'REMOVE_POSITION,' . $position + 1 . ' : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($this->output_file, 'REMOVE_POSITION,' . $position + 1 . ' : NOT SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        }
    }

    // Receives a start and ending position, moves the user from the start position to the ending position.
    public function move($start, $end)
    {
        if (array_key_exists($start, $this->queue) && array_key_exists($end, $this->queue)) {

            // Get the user from the position
            $user = $this->queue[$start];

            $user->setPosition($end);

            // Remove the user from the position
            array_splice($this->queue, $start, 1);

            // Move the user to the end position
            array_splice($this->queue, $end, 0, array($user));

            // Change users' position between start and end by 1
            for ($i = $start; $i <= $end; $i++) {
                $this->queue[$i]->setPosition($i);
            }
            file_put_contents($this->output_file, 'MOVE,' . $start + 1 . ',' . $end + 1 . ' : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($this->output_file, 'MOVE,' . $start + 1 . ',' . $end + 1 . ' : NOT SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        }
    }

    // Receives two positions, switches the user from each position with each other.
    public function switch($pos1, $pos2)
    {
        if (array_key_exists($pos1, $this->queue) && array_key_exists($pos2, $this->queue)) {
            // Switch users 
            $copyItem = $this->queue[$pos1];
            $this->queue[$pos1] = $this->queue[$pos2];
            $this->queue[$pos2] = $copyItem;

            // Change position
            $this->queue[$pos1]->setPosition($pos1);
            $this->queue[$pos2]->setPosition($pos2);

            file_put_contents($this->output_file, 'SWAP,' . $pos1 + 1 . ',' . $pos2 + 1 . ' : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($this->output_file, 'SWAP,' . $pos1 + 1 . ',' . $pos2 + 1 . ' : NOT SUCCCESFUL' . PHP_EOL, FILE_APPEND);
        }
    }

    // Reverses the order of the queue so the user in last position is now in first position.
    public function reverse()
    {
        $this->queue = array_reverse($this->queue);
        file_put_contents($this->output_file, 'REVERSE : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
    }

    // Prints the queue to stdout in the correct order,  each position should be on a new line and theformat per line should be “Position {position}: {user}”
    public function print()
    {
        foreach ($this->queue as $key => $value) {
            echo ("Position " . $key + 1 . ": " . $value->getId()) . "\n";
        }
        echo "\n";
        file_put_contents($this->output_file, 'PRINT : SUCCCESFUL' . PHP_EOL, FILE_APPEND);
    }
}
