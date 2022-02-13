<?php

include "./Management.php";

$man = new Management("output.txt");

$file = file_get_contents('./actions.txt', true);
$actions = explode("\n", $file);

foreach ($actions as $value) {
    $elements = explode(",", $value);
    $action = $elements[0];
    array_shift($elements);

    switch ($action) {
        case 'ADD':
            $user_id = $elements[0];
            $man->add($user_id);
            break;
        case 'REMOVE_USER':
            $user_id = $elements[0];
            $man->removeByUser($user_id);
            break;
        case 'REMOVE_POSITION':
            $pos = intval($elements[0]);
            $pos--;
            $man->removeByPosition($pos);
            break;
        case 'MOVE':
            $start = intval($elements[0]);
            $end = intval($elements[1]);
            $start--;
            $end--;

            $man->move($start, $end);
            break;
        case 'SWAP':
            $pos1 = intval($elements[0]);
            $pos2 = intval($elements[1]);
            $pos1--;
            $pos2--;

            $man->switch($pos1, $pos2);
            break;
        case 'REVERSE':
            $man->reverse();
            break;
        case 'PRINT':
            $man->print();
            break;

        default:
            # code...
            break;
    }
}
