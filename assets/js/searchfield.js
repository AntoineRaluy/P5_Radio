const $searchTrackForm = document.querySelector('.newtrack-form');
const $artistEntry = document.querySelector('#artist-name');
const $titleEntry = document.querySelector('#track-title');

const $sendTrackForm = document.querySelectorAll('[name="track_form"]');
const $newArtistEntry = document.querySelector('.data-entry-artist');
const $newTitleEntry = document.querySelector('.data-entry-title');
const $newGenreEntry = document.querySelector('.data-entry-genre');
const $newYearEntry = document.querySelector('.data-entry-year');

$searchTrackForm.addEventListener('submit', event => {
    event.preventDefault();
    matchMB($artistEntry.value, $titleEntry.value);
    });

    async function matchMB(artistEntry, titleEntry) {
        try {
        const mbUrl = await fetch('https://musicbrainz.org/ws/2/recording/?query=artist:'+artistEntry+'+AND+recording:'+titleEntry+'+AND+status:official&fmt=json');
        const trackSearch = await mbUrl.json();
        let mbTracks = [];
             
        trackSearch.recordings.forEach(trackFound => {
            if (trackFound.score == 100 && (trackFound.releases[0].date)) {
                let genreData = getGenre(trackFound.releases[0]['release-group'].id);

                let trackData = {
                    artist: trackFound['artist-credit'][0].name,
                    title: trackFound.title,
                    year: trackFound.releases[0].date.substring(0,4),
                    albumID: trackFound.releases[0]['release-group'].id,
                    genre: null,
                    length: trackFound.length,
                };

                mbTracks.push(trackData);
                console.log(trackData);
                displayTrack(trackData);
            }
        });

        console.log(mbTracks);
        } catch (error) {
            console.log(error)
        }
    }

    async function getGenre(albumID) {
        try {
        // mbTracks.foreach(
        const mbGenre = await fetch('https://musicbrainz.org/ws/2/release-group/'+albumID+'?inc=genres&fmt=json');
        const trackGenre = await mbGenre.json();   

        return trackGenre.genres[0].name;
        } catch (error) {
            console.log(error)
        }
    }

    function displayTrack(track) {
        const $songChoice = document.querySelector('#select-song');
        $songChoice.insertAdjacentHTML('beforeend', '<p> <input type="radio" class="trackSelect" name="trackChoice">' + track.artist + ' - ' + track.title + ' (' + fmtMSS((track.length)) + ') <em>[' + track.year + ']</em></p>');

        $songChoice.scrollIntoView();
        selectTrack(track);
    }

    function fmtMSS(ms){        
        ms = 1000*Math.round(ms/1000); // round to nearest second
        var d = new Date(ms);
        return(d.getUTCMinutes() + ':' + d.getUTCSeconds()); 

    }

    function selectTrack(track) {

        let selectedTrack = document.querySelector('.trackSelect');
        selectedTrack.addEventListener('change', e => {
            if(e.target.checked){
                document.querySelector('.data-entry-artist').value = track.artist;
                document.querySelector('.data-entry-title').value = track.title;
                document.querySelector('.data-entry-genre').value = track.genre;
                document.querySelector('.data-entry-year').value = track.year;
                }
            });  

        // let checkboxes = document.querySelectorAll("input[type=checkbox][name=trackSelect]");
        // let selectedTrack = []
        
        // checkboxes.forEach(checkbox => {
        //     checkbox.addEventListener('change', function() {
        //         selectedTrack = 
        //         Array.from(checkboxes) // Convert checkboxes to an array to use filter and map.
        //         .filter(i => i.checked) // Use Array.filter to remove unchecked checkboxes.
        //         .map(i => i.value) // Use Array.map to extract only the checkbox values from the array of objects.
      
        //     console.log(selectedTrack)
        // })
        // });

        
    }