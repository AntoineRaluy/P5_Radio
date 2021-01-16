const $searchTrackForm = document.querySelector('.newtrack-form');
const $artistEntry = document.querySelector('#artist-name');
const $titleEntry = document.querySelector('#track-title');
const $sendTrackForm = document.querySelector('#track-form');

    $searchTrackForm.addEventListener('submit', event => {
        event.preventDefault();
        matchMB($artistEntry.value, $titleEntry.value);
        });

    async function matchMB(artistEntry, titleEntry) {       // fetch MusicBrainz API
        try {
            const mbUrl = await fetch('https://musicbrainz.org/ws/2/recording/?query=artist:"'+artistEntry+'"+AND+recording:"'+titleEntry+'"+AND+status:official+NOT+video&fmt=json');  // search for official releases and audio format
            const trackSearch = await mbUrl.json();
            let mbTracks = [];
                
            trackSearch.recordings.forEach(trackFound => {
                if (trackFound.score >= 80                                  // matching request score
                    && (trackFound['first-release-date'])                   // tracks which have release date
                    && (trackFound.releases[0].status) == "Official"        // filtering by official release inside the objects
                    && !((trackFound.releases[0]['release-group']['secondary-types'])   
                        || ((trackFound.releases[0]['release-group']['secondary-types']) == "Compilation")
                        || ((trackFound.releases[0]['release-group']['secondary-types']) == "Live")) 
                                // accept tracks with no secondary type and reject Compilation and Live
                    && !((trackFound.releases[0].media[0].format) == "DVD")) { 
                                // exclude tracks from DVD media format (only CD & digital)
                        
                        let trackData = {
                            artist: trackFound['artist-credit'][0].name,
                            title: trackFound.title,
                            year: trackFound['first-release-date'].substring(0,4),
                            albumID: trackFound.releases[0]['release-group'].id,
                            mbid: trackFound.releases[0].id,
                            album: trackFound.releases[0].title,
                            genre: null,                // fetching genre later
                            length: trackFound.length,
                        };
                        mbTracks.push(trackData);
            }
        });
        getGenre(mbTracks);
        } catch (error) {
            console.log(error)
        }
    }

    async function getGenre(mbTracks) {         // fetch genre using MusicBrainz ID 
        try {
            for (mbTrack of mbTracks) {
                const mbGenre = await fetch('https://musicbrainz.org/ws/2/release-group/'+mbTrack.albumID+'?inc=genres&fmt=json');  
                const trackGenre = await mbGenre.json();
                if (trackGenre.genres[0]) {
                    mbTrack['genre'] = trackGenre.genres[0].name;
                } else {
                    mbTrack['genre'] = "Genre inconnu"
                }
            }
            displayTrack(mbTracks);
        } catch (error) {
            console.log(error)
        }
    }

    function displayTrack(mbTracks) {
        const $songChoice = document.querySelector('#select-song');
        const $songUnknown = document.querySelector('#notfound');
        $songChoice.innerHTML = null;
        if (mbTracks.length === 0) {                    // if search does not match, display div for manual request
            $songUnknown.style.display = "inline-block";
            $sendTrackForm.style.display = "none";
            }
        else {
            $songUnknown.style.display = "none";
        }
        mbTracks.forEach((mbTrack, index) => {
            $songChoice.insertAdjacentHTML('beforeend', '<p> <input type="radio" class="trackSelect['+index+']" name="trackChoice">' + mbTrack.artist + ' - ' + mbTrack.title + ' (' + fmtMSS((mbTrack.length)) + ') du disque : <em>' + mbTrack.album + ' [' + mbTrack.year + ']</em> </p>');
        })
        $songChoice.scrollIntoView();
        selectTrack(mbTracks);
    }

    function selectTrack(mbTracks) {        // fill track form accordingly to selection
        let trackChoice = document.querySelectorAll('input[name="trackChoice"]');
        if (trackChoice) {
            for (let i = 0; i < trackChoice.length; i++){
                trackChoice[i].addEventListener('change', () => {
                    document.querySelector('.data-entry-artist').value = mbTracks[i].artist;
                    document.querySelector('.data-entry-title').value = mbTracks[i].title;
                    document.querySelector('.data-entry-genre').value = mbTracks[i].genre;
                    document.querySelector('.data-entry-year').value = mbTracks[i].year;
                    document.querySelector('.data-entry-mbid').value = mbTracks[i].mbid;
                    $sendTrackForm.style.display = "block";
                    $sendTrackForm.scrollIntoView();
                })
            }
        }
    }
        
    function fmtMSS(ms){        // convert length in milliseconds to minutes:seconds
        if (isNaN(ms)) {
            return ("Durée inconnue")
        } else {     
            ms = 1000*Math.round(ms/1000);  // round to nearest second
            const d = new Date(ms);
            let minutes = d.getUTCMinutes();
            let seconds = d.getUTCSeconds();
            if(minutes < 10) {
                minutes = "0" + minutes;
            }
            if(seconds < 10) {
                seconds = "0" + seconds;
            }
            return(minutes + ':' + seconds); 
        }
    }
                
