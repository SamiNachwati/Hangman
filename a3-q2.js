// Add event listeners to the buttons


document.getElementById("Reset").addEventListener("click", function() { fetchData("reset"); });
document.getElementById("Letter_a").addEventListener("click", function() { fetchData("a"); });
document.getElementById("Letter_b").addEventListener("click", function() { fetchData("b"); });
document.getElementById("Letter_c").addEventListener("click", function() { fetchData("c"); });
document.getElementById("Letter_d").addEventListener("click", function() { fetchData("d"); });
document.getElementById("Letter_e").addEventListener("click", function() { fetchData("e"); });
document.getElementById("Letter_f").addEventListener("click", function() { fetchData("f"); });
document.getElementById("Letter_g").addEventListener("click", function() { fetchData("g"); });
document.getElementById("Letter_h").addEventListener("click", function() { fetchData("h"); });
document.getElementById("Letter_i").addEventListener("click", function() { fetchData("i"); });
document.getElementById("Letter_j").addEventListener("click", function() { fetchData("j"); });
document.getElementById("Letter_k").addEventListener("click", function() { fetchData("k"); });
document.getElementById("Letter_l").addEventListener("click", function() { fetchData("l"); });
document.getElementById("Letter_m").addEventListener("click", function() { fetchData("m"); });
document.getElementById("Letter_n").addEventListener("click", function() { fetchData("n"); });
document.getElementById("Letter_o").addEventListener("click", function() { fetchData("o"); });
document.getElementById("Letter_p").addEventListener("click", function() { fetchData("p"); });
document.getElementById("Letter_q").addEventListener("click", function() { fetchData("q"); });
document.getElementById("Letter_r").addEventListener("click", function() { fetchData("r"); });
document.getElementById("Letter_s").addEventListener("click", function() { fetchData("s"); });
document.getElementById("Letter_t").addEventListener("click", function() { fetchData("t"); });
document.getElementById("Letter_u").addEventListener("click", function() { fetchData("u"); });
document.getElementById("Letter_v").addEventListener("click", function() { fetchData("v"); });
document.getElementById("Letter_w").addEventListener("click", function() { fetchData("w"); });
document.getElementById("Letter_x").addEventListener("click", function() { fetchData("x"); });
document.getElementById("Letter_y").addEventListener("click", function() { fetchData("y"); });
document.getElementById("Letter_z").addEventListener("click", function() { fetchData("z"); });


fetch( "me.php" )
.then( response => {
    if ( !response.ok ) {
        throw new Error(response.status+" "+response.statusText)
    } else {
        return response.text();
    } 
} )
.then( data => document.getElementById( "student_info" ).innerHTML = data )
.catch( error => document.getElementById( "student_info" ).innerHTML = '<strong>'+error+'</strong>' );

// Check if a hidden element with a specific ID exists
function elementExists(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        return true;
    }
    return false;
}

// Function to send a fetch request to data_processor.php
function fetchData(choice) {

    if (choice == "reset") {
    // Build the URL with the selected parameters
        url = `hangman.php?mode=reset`;
    } else { 
        url = `hangman.php?letter=${choice}`;
    }

    // Fetch data from data_processor.php
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Clear previous results
            const resultContainer = document.getElementById("resultContainer");
            resultContainer.innerHTML = "";
            const status = data["status"];

            resultContainer.innerHTML = `Status: ${status}<br>`;
            if( data["alphabet"] ) { const alphabet = data["alphabet"]; resultContainer.innerHTML += `The following letters are available: ${alphabet}<br>`; }
            if( data["guesses"] ) { const guesses = data["guesses"]; resultContainer.innerHTML += `So far you have guessed: ${guesses}<br>`; }
            if( data["secret"] ) { const secret = data["secret"]; resultContainer.innerHTML += `Your word clue is: ${secret}<br>`; }
            if( data["strikes"] ) { const strikes = data["strikes"]; resultContainer.innerHTML += `You have made: ${strikes} incorrect guesses<br>`; }
        })
        .catch(error => {
            console.error("Error fetching data: " + error);
        });
}