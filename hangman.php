<?php
//I Sami Nachwati, 000879289, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else.
/**
 * @author Sami Nachwati
 * @version 202335.00
 * @package COMP 10260 Assignment 3
 */

 // begin the session
session_start();

// the mode determined if it is reset or not
$mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_SPECIAL_CHARS);

// the letter clicked from the user
$letter = strtolower(filter_input(INPUT_GET, 'letter', FILTER_SANITIZE_SPECIAL_CHARS));

// array used to read all words from word file
$words = [];

// initialize guesses as an empty string
$guesses = "";


/**
 * @param no arguments taken
 * Method which processes the file name 'wordlist.txt', reads it, and pushes the words into the words array.
 * @return no return value
 */
function readData(){
    global $words;
    $fh = fopen("wordlist.txt", 'r');
    while(!feof($fh)){
        $line = trim(fgets($fh));
        if($line !== "" && $line !== null){
            array_push($words, $line);
        }
    }
    fclose($fh);
}




/**
 * @param no arguments taken
 * Method that checks the given letter clicked by the user. This method handles many conditions that the user can cause by clicking the letter
 * @return associative array 
 */
function checkLetter(){
    global $secret_word;
    global $letter;
    global $letters;
    global $data;
    global $words;
    global $sortedLetters;
    // initialize the status of the game when the user starts
    $data['status'] = 'you are playing the game now';
    if(isset($_SESSION['guesses']) || !isset($_SESSION['guesses'])){
        $letters = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
        $_SESSION['guesses'][] = $letter; 
        if(!isset($_SESSION['lettersPicked'])){
            $_SESSION['lettersPicked'] = [];
        }
        // add each letter to an array
        array_push($_SESSION['lettersPicked'], $letter);
        // store the letters as guesses with only showing unique letters for any letter user clicks
        $_SESSION['guesses'] = array_unique($_SESSION['guesses']);
        // sort the guesses
        sort($_SESSION['guesses']);
        // convert it back to a string
        $sortedLetters = implode("", $_SESSION['guesses']);
        // remove any letters guesses by the user from the alphabet
        $letters = implode(array_diff($letters, $_SESSION['guesses']));
    }
    else{
        $_SESSION['guesses'] = [];
    }
    
    if (!isset($_SESSION['wordArr']) || empty($_SESSION['wordArr']) || !isset($_SESSION['selectedWord'])) {
        readData();
        $_SESSION['wordArr'] = $words;
        shuffle($_SESSION['wordArr']);
        $_SESSION['selectedWord'] = $_SESSION['wordArr'][count($_SESSION['wordArr'])-1];
    } 
    $secret_word = $_SESSION['selectedWord'];
    


    if(!isset($_SESSION['hiddenWord'])){
        // replace the elements in the actual word with dashes, so that it is hidden from user
        $_SESSION['hiddenWord'] = str_repeat("-", strlen($secret_word));
    }

    // if the letter is inside the secret word,
    // show the letter in the secret word but keep
    // the unguesses letters still hidden
    for($i=0; $i<strlen($secret_word); $i++){
        if($letter == $secret_word[$i]){
            $_SESSION['hiddenWord'][$i] = $secret_word[$i];
            $data['secret'] = $_SESSION['hiddenWord'];
        }
    }
    global $arr;
    $arr = [];
    array_push($arr, $letter);

    // if my word does not contain the letter picked
    // increment the incorrect guesses by 1
    // ensure that if the incorrect letter was already guessed, do not increment for that letter again.
    if(strpos($secret_word, $letter) == false) {        
        if (!isset($_SESSION['numGuesses'])) {
            $_SESSION['numGuesses'] = 1; 
        } else {
            if(array_count_values($_SESSION['lettersPicked'])[$letter] == 1){
                $_SESSION['numGuesses']++;
            }
        }
    }
    else {
        if (!isset($_SESSION['numGuesses'])) {
            $_SESSION['numGuesses'] = 0; 
        }
    }
    // if the user guesses 7 tries or more, they lost
    if ($_SESSION['numGuesses'] > 6) {
        $_SESSION['hiddenWord'] = $secret_word;
        $data['status'] = 'you have lost the game!';
    }

    // if the user guesses the letters and still has guesses less than 7, they won
    if($_SESSION['hiddenWord'] == $secret_word && $_SESSION['numGuesses'] < 7){
        $data['status'] = 'you won the game!';
    }

    
    // store back the values to the associative array
    $data['guesses'] = $sortedLetters;
    $data['alphabet'] = $letters;
    $data['secret'] = $_SESSION['hiddenWord'];
    $data['strikes'] = $_SESSION['numGuesses'];
    $data['word'] = $secret_word;

    // return the array containing my data
    return $data;

    // unset any session after this point
    session_unset();
}


// if the mode is reset
if ($mode == 'reset') {
    // Reset session variables
    unset($_SESSION['guesses']);
    unset($_SESSION['numGuesses']);
    unset($_SESSION['hiddenWord']);
    unset($_SESSION['selectedWord']);
    unset($_SESSION['lettersPicked']);

    global $letters;
    global $secret_word;
    $letters = 'abcdefghijklmnopqrstuvwxyz';
    $_SESSION['guesses'][] = '';

    // if my array is not set or is ever empty, store my words back to the array
    if (!isset($_SESSION['wordArr']) || empty($_SESSION['wordArr'])) {
        readData();
        $_SESSION['wordArr'] = $words;
    }
    
    // randomize the array of words
    shuffle($_SESSION['wordArr']);

    // remove the last element and assign that to the secret word, to ensure the word does not repeat again for a given game session
    $_SESSION['selectedWord'] = array_pop($_SESSION['wordArr']);
    $secret_word = $_SESSION['selectedWord'];

    // reset number of guesses to 0
    $_SESSION['numGuesses'] = 0;
    $_SESSION['hiddenWord'] = str_repeat("-", strlen($secret_word));
    $data = [
        'secret' => $_SESSION['hiddenWord'],
        'status' => 'new game started'
    ];

    // display the json encoded data values
    echo json_encode($data);
} 


// if the user does not select reset, call the checkLetter() method
else {
    echo json_encode(checkLetter());
}









?>