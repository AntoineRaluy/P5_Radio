releaseCover(mbid);

async function releaseCover(mbid) {
    const caUrl = 'https://coverartarchive.org/release/'+mbid+'/front-250';
    urlExists(caUrl, function(exists) {
        if (exists) {
            insertCover(caUrl);
        } else {
            rgMbid(mbid);
        }
      });
}

function urlExists(url, callback) {
    fetch(url, { method: 'head' })
    .then(function(status) {
      callback(status.ok)
    });
  }

async function rgMbid(mbid) {
    const mbUrl = await fetch('https://musicbrainz.org/ws/2/release/?query=mbid:'+mbid+'&fmt=json');
    const trackDetails = await mbUrl.json();
    const mainMbid = trackDetails.releases[0]['release-group'].id;
    mainCover(mainMbid);
}

async function mainCover(mainMbid) {
    const caUrl = 'https://coverartarchive.org/release-group/'+mainMbid+'/front-250';
    urlExists(caUrl, function(exists) {
        if (exists) {
          insertCover(caUrl);
        } else {
            console.log("Il n'y a pas d'illustration pour ce morceau.")
        }
      });
}

function insertCover(url) {
    let coverArt = new Image();
    const artworkDiv = document.getElementById('artwork');

    coverArt.onload = function() {
        artworkDiv.appendChild(coverArt); 
    }
    coverArt.src = ''+url+'';
}
