<html>
<head>
<title>Karaoke PI</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="karaoke.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="icons.js"></script>
<script>
function shutdown()
{
    if ( confirm("Are you sure you want to\nshutdown the Karaoke Pi\n machine?" ) )
    {
        location.assign("shutdown.php");
    }
}
</script>
</head>
<body>
<script>
var arr_Favorite = [];
var arr_My_Rate = [];
var arr_Avg_Rate = [];
var arr_YouTube  = [];
var arr_Songs = [];
var str_video = "";

var str_c_status = "STANDBY";

function toggleFullScreen(bool_Inc)
{
    html_div_style = document.getElementById('screen_div').style;
    if (bool_Inc)
    {
        html_div_style.position = "absolute";
        html_div_style.top = 0;
        html_div_style.left = 0;
        html_div_style.width = "100%";
    }
    else
    {
        html_div_style.position = "inherit";
        html_div_style.top = "inherit";
        html_div_style.left = "inherit";
        html_div_style.height = "100%";
        html_div_style.width = "100%";
    }

}

var getJSON = function(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'json';
        xhr.onload = function() {
            var status = xhr.status;
            if (status === 200) {
                callback(null, xhr.response);
            } else {
                callback(status, xhr.response);
            }
        };
        xhr.send();
};

function getSongList()
{
    getJSON('./server/data_list.php',
        function(err, data) {
            if (err !== null) {
                alert('Something went wrong: ' + err);
            } else {
                arr_Songs = data;
                str_html = "";
                if ( data.length == 0 )
                {
                    str_html = "No videos are currently loaded";
                }
                for (x = 0; x < data.length; ++x)
                { 
                    str_html += "<button>\n";
                    str_html += "<table width='100%'><tr><td width='68%' onclick=\"\";>\n";
                    str_html += "<span style='font-weight:900;text-align:left;font-size:25px'>" +  data[x].title + "</span><br />\n";
                    str_html += "<span style='text-align:left;'>" + data[x].artist + "</span><br />\n";
                    str_html += "<span style='text-align:left;font-weight:700;color:#" + data[x].color ;
                    str_html += "'>Queued by " + data[x].queued_by + "</span>\n";
                    str_html += "</td></tr>";
                    str_html += "</table></button><br /><br />\n";


                }
                document.getElementById("td_list").innerHTML = str_html;
            }
        });

    setTimeout(function () {getSongList()}, 1000);
    html_screen = document.getElementById("screen");

    if ( html_screen.src.endsWith("~WAITING.mp4") && arr_Songs.length > 0 )
    {
        playVideo(0);
        str_c_status = "STANDBY"
    }
    else
    {
//        html_screen.play();
//        console.log("getSongList started playing");
    }
}

function getStatus()
{
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET","./server/getStatus.php",true);
        xhttp.responseType = 'xml';
        xhttp.onload = function() {
            parser = new DOMParser();
            xmlDoc = parser.parseFromString(xhttp.responseText,"text/xml");
            str_n_status = xmlDoc.getElementById("tngstatus").textContent;

            if (str_n_status != str_c_status)
            {
                if (str_n_status == "PAUSED")
                {
                    document.getElementById("screen").pause();
                    console.log(document.getElementById("screen"));
                    toggleFullScreen(0);
                    console.log("paused");
                    str_c_status = str_n_status;
                }
                else if (str_n_status == "PLAYED")
                {
                    document.getElementById("screen").play();
                    console.log("getStatus started playing");
                    toggleFullScreen(1);
                    console.log("play");
                    str_c_status = str_n_status;
                }
                else if (str_n_status == "SKIPPING")
                {
                    console.log("skipping");
                    playVideo(1);
                }
            }
        }

        xhttp.send();

    setTimeout(function () {getStatus()}, 10000);
        
}


function playVideo(int_queue)
{
    console.log ("Queue = " + int_queue + ", Arr_Songs = " + arr_Songs.length);
    html_screen = document.getElementById("screen");

    // When the video is with the loading screen
    if ( html_screen.src.endsWith("~LOADING.mp4") )
    {
        str_video = arr_Songs[0].youtube_id;
        html_screen.src = "./videos/" + str_video + ".mp4";
        toggleFullScreen(1);
        console.log("Start Video");
        str_c_status = "PLAYED";
    
        //Remove song from queue       
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET","./server/nextSong.php",true);
        xhttp.send();
        console.log("I popped off a song");
    }

    // Start the loading screen
    else if ( arr_Songs.length > int_queue )
    {
        document.getElementById("screen").src= "./videos/~LOADING.mp4";
        toggleFullScreen(0);
        console.log("Start Loading");
        str_c_status = "PLAYED";
    }

    // There are no songs in the list
    else
    {
        document.getElementById("screen").src= "./videos/~WAITING.mp4";
        toggleFullScreen(0);
        console.log("Start Waiting");
        str_c_status = "STANDBY";
    }

    /*
    if ( int_queue > 0 )
    {
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET","./server/nextSong.php",true);
        xhttp.send();
     }
     */
}



</script>
<table>
<tr>
<td width="31%" style='text-align:center;vertical-align:top'>
<h1>KaraokePi</h1><img src="qr_code.png" />
<hr>
<div id="td_list">
<button onclick="getSongList();getStatus();">Start</button>
</div>
</td>
<td>
<div id="screen_div" style='background-color:black'>
<video id="screen" height="100%" width="100%" src="./videos/~WAITING.mp4" controls autoplay onended="playVideo(1)">
</video>
</div>
</td>
</tr>
</table>
</body>
</html>
