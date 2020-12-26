const $searchTrackForm = document.querySelector('.newtrack-form');
const $artistEntry = document.querySelector('#artist-name');
const $titleEntry = document.querySelector('#track-title');

    $searchTrackForm.addEventListener('submit', event => {
        event.preventDefault();
        matchMB($artistEntry.value, $titleEntry.value);
        });

    async function matchMB(artistEntry, titleEntry) {
        try {
        const mbUrl = await fetch('https://musicbrainz.org/ws/2/recording/?query=artist:'+artistEntry+'+AND+recording:'+titleEntry+'+AND+status:official+AND+NOT+secondarytype:Compilation+AND+NOT+secondarytype:Live&limit=10&fmt=json');
        const trackSearch = await mbUrl.json();
        let mbTracks = [];
             
        trackSearch.recordings.forEach(trackFound => {
            if (trackFound.score == 100 && (trackFound.releases[0].date)) {
                let trackData = {
                    artist: trackFound['artist-credit'][0].name,
                    title: trackFound.title,
                    year: trackFound.releases[0].date.substring(0,4),
                    albumID: trackFound.releases[0]['release-group'].id,
                    album: trackFound.releases[0].title,
                    genre: null,
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

    async function getGenre(mbTracks) {
        try {
            for (mbTrack of mbTracks) {
                const mbGenre = await fetch('https://musicbrainz.org/ws/2/release-group/'+mbTrack.albumID+'?inc=genres&fmt=json');
                const trackGenre = await mbGenre.json();
                console.log(trackGenre);
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
        $songChoice.innerHTML = null;
        
        mbTracks.forEach((mbTrack, index) => {
            $songChoice.insertAdjacentHTML('beforeend', '<p> <input type="radio" class="trackSelect['+index+']" name="trackChoice">' + mbTrack.artist + ' - ' + mbTrack.title + ' (' + fmtMSS((mbTrack.length)) + ') du disque : <em>' + mbTrack.album + ' [' + mbTrack.year + ']</em> </p>');
        })
        $songChoice.scrollIntoView();
        selectTrack(mbTracks);
    }

    function fmtMSS(ms){
        if (isNaN(ms)) {
            return ("Durée inconnue")
        } else {        
        ms = 1000*Math.round(ms/1000); // round to nearest second
        var d = new Date(ms);
        return(d.getUTCMinutes() + ':' + d.getUTCSeconds()); 
        }

    }

    function selectTrack(mbTracks) {
        const $sendTrackForm = document.querySelector('#track_form_artist');
        let trackChoice = document.querySelectorAll('input[name="trackChoice"]');
        if (trackChoice) {
            for (let i = 0; i < trackChoice.length; i++){
                trackChoice[i].addEventListener('change', () => {
                    document.querySelector('.data-entry-artist').value = mbTracks[i].artist;
                    document.querySelector('.data-entry-title').value = mbTracks[i].title;
                    document.querySelector('.data-entry-genre').value = mbTracks[i].genre;
                    document.querySelector('.data-entry-year').value = mbTracks[i].year;
                    $sendTrackForm.scrollIntoView();
                })
            }
        }
    }
        
                
