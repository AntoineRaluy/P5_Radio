releaseCover(mbid);

function releaseCover(mbid) {             // fetch cover for the track using musicbrainz ID & CoverArtArchive
    const caUrl = 'https://coverartarchive.org/release/'+mbid+'/front-250';
    urlExists(caUrl, function(exists) {
        if (exists) {
            insertCover(caUrl);
        } else {
            rgMbid(mbid);               // get ID of main release
        }
      });
}

function mainCover(mainMbid) {      // fetch cover art for the main release
    const caUrl = 'https://coverartarchive.org/release-group/'+mainMbid+'/front-250';
    urlExists(caUrl, function(exists) {
        if (exists) {
            insertCover(caUrl);
        } else {
            defaultCover();         
        }
      });
}

async function rgMbid(mbid) {           // get ID of the main release of the track
    try {
        const mbUrl = await fetch('https://musicbrainz.org/ws/2/release/?query=mbid:'+mbid+'&fmt=json');
        const trackDetails = await mbUrl.json();
        const mainMbid = trackDetails.releases[0]['release-group'].id;
        mainCover(mainMbid);
    } catch (error) {
        console.log(error);
    }
}

function urlExists(url, callback) {     // check if URL (and the cover) exists
    fetch(url, { method: 'head' })
    .then(function(status) {
      callback(status.ok)
    });
  }

function insertCover(url) {         // insert cover into the track deatils template
    let coverArt = new Image();
    const artworkDiv = document.getElementById('artwork');

    coverArt.onload = function() {
        artworkDiv.appendChild(coverArt); 
    }
    coverArt.src = ''+url+'';
}

function defaultCover() {           // insert default cover art if nothing matched
    let defaultCover = new Image();
    const artworkDiv = document.getElementById('artwork');

    defaultCover.onload = function() {
        artworkDiv.appendChild(defaultCover); 
    }
    defaultCover.src = "../build/defaultCover.png";
}
