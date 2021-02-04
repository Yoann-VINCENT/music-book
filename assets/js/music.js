const musicButton = document.querySelector("#music_button");
const song = document.querySelector("#song").value;

let audio = new Audio('/uploads/songs/' + song);

musicButton.addEventListener("click", function()
    {
        audio.currentTime = 0;
        audio.paused ? audio.play() : audio.pause();
    }
);