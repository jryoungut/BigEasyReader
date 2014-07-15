var SpritzHelper = {};
var Settings = {
    'ColorThemeID':'1',
    'ColorTheme':'Dark Theme',
    'Size':'60',
    'Speed':'180',
    'Fixation':'0,0'
};
var NewsReadInfo = {
    'Link':'',
    'Description':''
};
var CalculatedRedicleWidth = $(window).width() * parseInt(Settings.Size)/100;
var CustomOptions = {
    'debugLevel':   0,
    'redicleWidth':     CalculatedRedicleWidth,
    'redicleHeight':    CalculatedRedicleWidth * SpritzHelper.verticalScaleFactor,
    'defaultSpeed':     Settings.Speed,
    'speedItems':   [250, 300, 350, 400, 450, 500, 550, 600],
    'controlButtons': [],
    'anonymousEnabled': true,
    'header': { //show above the redicle
        'close': false, //close "x" button
        'closeHandler': '', //optional callback the dev can set, otherwise use our default
    },
    'controlTitles' : {},
    'placeholderText': { //full sentence text that displays before/after a spritz
        'startText': "Click to spritz.",
        'startTextColor': "#bababa",
        'endText': "",
        'endTextColor': ""
    },
    //"advanced" redicle options
    redicle: {
        'lineStrokeWidth': .125,
        'backgroundColor': "#000011",
        'textNormalPaintColor': "#eeeeff",
        'textHighlightPaintColor': "#FAFA00", //red ORP
        'redicleLineColor': "#aaa",
        'redicleLineWidth': 20,
        'countdownTime': 750,
        'countdownColor': "#A8A8A8",
        'countdownSlice': 5 // 5 milliseconds
    }
};
var ContentUrl = 'http://www.archives.gov/exhibits/charters/print_friendly.html?page=declaration_transcript_content.html&title=NARA%20%7C%20The%20Declaration%20of%20Independence%3A%20A%20Transcription';
var TheSpritzerControl;

SpritzHelper.host = window.location.host;
SpritzHelper.http = location.protocol;
SpritzHelper.slashes = SpritzHelper.http.concat("//");
//SpritzHelper.locandfile = ':81/sr/login_success.html';
SpritzHelper.locandfile = '/bigeasyreader/login_success.html';
SpritzHelper.host = SpritzHelper.slashes.concat(SpritzHelper.host);
SpritzHelper.locandfile = SpritzHelper.host.concat(SpritzHelper.locandfile);
SpritzHelper.bookLocation = '';
SpritzHelper.currentTextInfo = '';
SpritzHelper.SPRITZ_STATE_RESET = 0;
SpritzHelper.SPRITZ_STATE_READING = 1;
SpritzHelper.SPRITZ_STATE_PAUSED = 2;
SpritzHelper.verticalScaleFactor = .2;
SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
SpritzHelper.spritzText;

var SpritzSettings = {
    clientId: "cc7314548848c6af2",
    redirectUri: SpritzHelper.locandfile
};
SpritzHelper.spritzController = null;

SpritzHelper.onSpritzifySuccess = function (spritzText) {
    SpritzHelper.spritzController.startSpritzing(spritzText);

    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_READING;
    SpritzHelper.doBtnUI();
};

SpritzHelper.onSpritzifyError = function (error) {
    alert("Unable to Spritz: " + error.message);
};

SpritzHelper.setSpritzText = function (spritzText) {
    // Pull the SpritzerController from this spritzer element and call setSpritzText
    TheSpritzerControl.data("controller").setSpritzText(spritzText);
};

var onFetchSuccessController = function (spritzText) {
    SpritzHelper.setSpritzText(spritzText);

    SpritzHelper.spritzController.startSpritzing(spritzText);
    SpritzHelper.spritzText = spritzText;

    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_READING;
    SpritzHelper.doBtnUI();
};

var onFetchError = function (error) {
    alert("Unable to Spritz: " + error.message);
};

SpritzHelper.clearTextClick = function () {
    $("#inputText").val("");
};

SpritzHelper.onStartSpritzClick = function (event) {
    // SpritzHelper.currentTextInfo = { "url": SpritzHelper.host + '/ezbigread/' + SpritzHelper.bookLocation };
    //SpritzHelper.currentTextInfo = { "url": 'http://www.zaycounlimited.com/ezbigread/Books/PoetryOfASoldier.txt' };
    if(ContentUrl.length === 0){
        $.mobile.back();
    }
    else{
        SpritzClient.fetchContents(ContentUrl, onFetchSuccessController, onFetchError);
    }
};

SpritzHelper.onTextStartSpritzClick = function (event) {
    var text = $('#inputText').val();
    var locale = "en_us;";

    // Send to SpritzEngine to translate
    SpritzClient.spritzify(text, locale, SpritzHelper.onSpritzifySuccess, SpritzHelper.onSpritzifyError);
};

SpritzHelper.onWebLinkStartSpritzClick = function (event) {
    var link = $('#inputText').val();
    SpritzClient.fetchContents(link, onFetchSuccessController, onFetchError);
    $('#startSpritz').addClass('hide');
    $('#pauseSpritz').removeClass('hide');
};

SpritzHelper.onTogglePauseSpritzClick = function (event) {
    var ctrl = $('#togglepauseSpritz');
    if (SpritzHelper.spritzingState === SpritzHelper.SPRITZ_STATE_READING) {
        SpritzHelper.spritzController.pauseSpritzing();
        SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_PAUSED;
        $(ctrl).attr('src', 'images/Play.png');
    }
    else {
        SpritzHelper.spritzController.resumeSpritzing();
        SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_READING;
        $(ctrl).attr('src', 'images/Pause.png');
    }
};

SpritzHelper.onBtnResetClick = function (event) {
    SpritzHelper.spritzController.stopSpritzing();
    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
    SpritzHelper.doBtnUI();
};

SpritzHelper.onBtnBackSentenceClick = function (event) {
    SpritzHelper.spritzController.spritzPanel.goToPreviousSentence();
    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_PAUSED;
    $('#togglepauseSpritz').attr('src', 'images/Play.png');
};


SpritzHelper.onBtnNextSentenceClick = function (event) {
    SpritzHelper.spritzController.spritzPanel.goToNextSentence();
    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_PAUSED;
    $('#togglepauseSpritz').attr('src', 'images/Play.png');
};

SpritzHelper.doBtnUI = function () {
    switch (SpritzHelper.spritzingState) {
        case SpritzHelper.SPRITZ_STATE_RESET:
            $('#startSpritz').removeClass('hide');
            $('#togglepauseSpritz').addClass('hide');
            $('#btnReset').addClass('hide');
            $('#btnBackSentence').addClass('hide');
            $('#btnBackWord').addClass('hide');
            $('#btnNextWord').addClass('hide');
            $('#btnNextSentence').addClass('hide');
            break;
        case SpritzHelper.SPRITZ_STATE_READING:
        case SpritzHelper.SPRITZ_STATE_PAUSED:
            $('#startSpritz').addClass('hide');
            $('#togglepauseSpritz').removeClass('hide');
            $('#btnReset').removeClass('hide');
            $('#btnBackSentence').removeClass('hide');
            $('#btnBackWord').removeClass('hide');
            $('#btnNextWord').removeClass('hide');
            $('#btnNextSentence').removeClass('hide');
            break;
    }
    
};

SpritzHelper.goForwardOneWord = function() {
    var index = SpritzHelper.spritzText.getCurrentIndex();
    SpritzHelper.spritzController.spritzPanel.seek( index + 1 );
    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_PAUSED;
    $('#togglepauseSpritz').attr('src', 'images/Play.png');
};

SpritzHelper.goBackOneWord = function() {
    var index = SpritzHelper.spritzText.getCurrentIndex();
    SpritzHelper.spritzController.spritzPanel.seek( index - 1 );
    SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_PAUSED;
    $('#togglepauseSpritz').attr('src', 'images/Play.png');
};

SpritzHelper.setSpeed = function (event) {
    // var spd = $('#speedSlider').val();
    // $('#spritzer').data('controller').setSpeed(spd);
};



SpritzHelper.init = function () {
    var container = TheSpritzerControl;
    if(container != null){
        container.on("onSpritzComplete", function (event) {
            SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
            SpritzHelper.doBtnUI();
        });
    }
    $("#startSpritz").on("click", SpritzHelper.onStartSpritzClick);
    $("#togglepauseSpritz").on("click", SpritzHelper.onTogglePauseSpritzClick);
    $("#btnReset").on("click", SpritzHelper.onBtnResetClick);
    $("#btnBackSentence").on("click", SpritzHelper.onBtnBackSentenceClick);
    $("#btnBackWord").on("click", SpritzHelper.goBackOneWord);
    $("#btnNextWord").on("click", SpritzHelper.goForwardOneWord);
    $("#btnNextSentence").on("click", SpritzHelper.onBtnNextSentenceClick);
    $("#clear").on("click", SpritzHelper.clearTextClick);
    SpritzHelper.doBtnUI();
};  // END init


SpritzHelper.GetLineWidth = function(size){
    var lw = 0;
    switch(parseInt(size)){
        case 100:
            lw = 20;
            break;
        case 90:
            lw = 17;
            break;
        case 80:
            lw = 14;
            break;
        case 70:
            lw = 11;
            break;
        case 60:
            lw = 9;
            break;
        case 50:
            lw = 7;
            break;
        case 40:
            lw = 5;
            break;
        case 30:
            lw = 3;
            break;
        case 40:
            lw = 1;
            break;
        
    }
    
    return lw;
};


 SpritzHelper.SetThemeColors = function(){
    if(Settings.ColorTheme === 'Dark Theme'){
        $('div').removeClass('themeLight');
        $('div').addClass('themeDark');
        $('h3').removeClass('themeLight');
        $('h3').addClass('themeDark');
        $('a').removeClass('themeLight');
        $('a').addClass('themeDark');
        $('#spnDark').removeClass('themeLight');
        $('#spnDark').addClass('themeDark');
        $('#spnLight').removeClass('themeLight');
        $('#spnLight').addClass('themeDark');
        $('#spnDark').html('&#10152;');
        $('#spnLight').html('');
        $('.spritzer-container').removeClass('themeLight');
        $('.spritzer-container').addClass('themeDark');
    }
    else{
        $('div').removeClass('themeDark');
        $('div').addClass('themeLight');
        $('h3').removeClass('themeDark');
        $('h3').addClass('themeLight');
        $('a').removeClass('themeDark');
        $('a').addClass('themeLight');
        $('#spnDark').removeClass('themeDark');
        $('#spnDark').addClass('themeLight');
        $('#spnLight').removeClass('themeDark');
        $('#spnLight').addClass('themeLight');
        $('#spnDark').html('');
        $('#spnLight').html('&#10152;');
        $('.spritzer-container').removeClass('themeDark');
        $('.spritzer-container').addClass('themeLight');
    }
};

 SpritzHelper.SetFontSize = function(){
    switch(Settings.Size){
        case "20":
        case "30":
        case "40":
            $('div').addClass('themeFontSmall');
            $('h3').addClass('themeFontSmall');
            $('a').addClass('themeFontSmall');
            break;
        case "50":
        case "60":
        case "70":
            $('div').addClass('themeFontMedium');
            $('h3').addClass('themeFontMedium');
            $('a').addClass('themeFontMedium');
            break;
        case "80":
        case "90":
        case "100":
            $('div').addClass('themeFontLarge');
            $('h3').addClass('themeFontLarge');
            $('a').addClass('themeFontLarge');
            break;
    }
};

        
SpritzHelper.SaveThemeColors = function (){
    var dataString = {
        "settingID" : "1",
        "settingValue" : String(Settings.ColorThemeID)
    };
    
    $.ajax({  
        type: "POST",  
        url: "settingsFunctions.php/SaveSettings",  
        data:  "settingID=" + dataString.settingID + "&" + "settingValue=" + dataString.settingValue,
        success: function(data){  
            //alert(data);  
        }  
    });  
};

SpritzHelper.SaveSize = function (){
    $.ajax({  
        type: "POST",  
        url: "settingsFunctions.php/SaveSettings",  
        data:  "settingID=2&settingValue=" + String(Settings.Size),
        success: function(data){  
            //alert(data);  
        }  
    });  
};

SpritzHelper.SaveSpeed = function (){
    $.ajax({  
        type: "POST",  
        url: "settingsFunctions.php/SaveSettings",  
        data:  "settingID=3&settingValue=" + Settings.Speed,
        success: function(data){  
            //alert(data);  
        }  
    });  
};

SpritzHelper.SaveFixation = function (){
    $.ajax({  
        type: "POST",  
        url: "settingsFunctions.php/SaveSettings",  
        data:  "settingID=4&settingValue=" + Settings.Fixation,
        success: function(data){  
            //alert(data);  
        }  
    });  
};


SpritzHelper.InitSpritz = function (autoStart) {
  //var contentUrl = 'http://www.zaycounlimited.com/bigread/Books/PoetryOfASoldier.txt';
  var spritzer = TheSpritzerControl;
  var spritzerId = $(spritzer).prop('id');
  
  spritzer.data('url', ContentUrl);

  SpritzHelper.spritzController = new SPRITZ.spritzinc.SpritzerController(CustomOptions);
  SpritzHelper.spritzController.spritzClient = SpritzClient;
  SpritzHelper.spritzController.attach(spritzer);
    
  // Is there a user logged in?
    //if (SpritzClient.isUserLoggedIn() && autoStart) {
    if (autoStart) {
      // Yes, so we'll kick off the content retrieval process, and start the Spritz
      // if and when retrieval completes successfully.
        SpritzClient.fetchContents(ContentUrl, onFetchSuccessController, onFetchError);
    } else {
      // No, so we'll let the user initiate the Spritz. 
    }
};

SpritzHelper.SetupSpritzerUI = function(){
    CalculatedRedicleWidth = $(window).width() * parseInt(Settings.Size)/100;

    CustomOptions['redicleWidth'] = CalculatedRedicleWidth;
    CustomOptions['redicleHeight'] = CalculatedRedicleWidth * SpritzHelper.verticalScaleFactor;

    var redicleLineWidth = SpritzHelper.GetLineWidth(Settings.Size);
    CustomOptions.redicle['redicleLineWidth'] = redicleLineWidth;
};

SpritzHelper.CenterSpritzerControl = function (ctrl, page){
    var cW = CalculatedRedicleWidth;
    var cH = CalculatedRedicleWidth*SpritzHelper.verticalScaleFactor;
    var win = $(window);
    var winWidth = win.width();
    var winHeight = win.height() - $('#' + $(page).prop('id') + ' .divCtrlHolder').position().top -150;
    
    //ctrl.css('margin-top', ((winHeight/2) - cH/2) + 'px');
    ctrl.css('margin-top', '200px');
    ctrl.css('margin-left', ((winWidth/2) - cW/2) + 'px');
    
};

SpritzHelper.ResetFixation = function (){
    Settings.Fixation = '10,10';
    SpritzHelper.SaveFixation();
    SpritzHelper.InitCanvas();
    SpritzHelper.RefreshPage();
};

SpritzHelper.NoneFixation = function (){
    Settings.Fixation = 'None';
    SpritzHelper.SaveFixation();
    SpritzHelper.InitCanvas();
    SpritzHelper.RefreshPage();
};

SpritzHelper.RefreshPage = function () {
  window.location.reload(true);
};

SpritzHelper.GoToNewsReader = function(lnk){
    NewsReadInfo.Link = lnk;
    //$.mobile.changePage('#pgNewsRead');
    //$.mobile.pageContainer.pagecontainer ("change", "#pgNewsRead", {reloadPage: false});
    window.location.href = "#pgNewsRead";
};

SpritzHelper.DoGlassCover = function (){
    if(SpritzHelper.spritzingState === SpritzHelper.SPRITZ_STATE_RESET ){
        SpritzHelper.onStartSpritzClick();
    }
    else{
        SpritzHelper.onTogglePauseSpritzClick();
    }
};

SpritzHelper.InitCanvas = function (){
    window.addEventListener("load", windowLoadHandler, false);
    function windowLoadHandler() {
        canvasApp();
    }
    
    function canvasSupport() {
        return Modernizr.canvas;
    }
    
    function canvasApp() {
        if (!canvasSupport()) {
            return;
        }
        
        var theCanvas = document.getElementById("canvasOne");
        $(theCanvas).css('width', $('#canvasHolder').width());
        $(theCanvas).css('height', $('#canvasHolder').height());
        var context = theCanvas.getContext("2d");
        
        init();
        
        var shapes;
        var numShapes;
        var dragIndex;
        var dragging;
        var mouseX;
        var mouseY;
        var dragHoldX;
        var dragHoldY;
        
        function init() {
            numShapes = 1;
            shapes = [];
            
            makeShapes();
            
            drawScreen();
            
            theCanvas.addEventListener("mousedown", mouseDownListener, false);
        }
        
        function makeShapes() {
            var i;
            var tempX = Settings.Fixation.split(',')[0];
            var tempY = Settings.Fixation.split(',')[1];
            var tempRad = 10;
            var tempR = 255;
            var tempG = 255;
            var tempB = 255;
            var tempColor = "rgb(" + tempR + "," + tempG + "," + tempB +")";
            tempShape = {x:tempX, y:tempY, rad:tempRad, color:tempColor};
            shapes.push(tempShape);
        }
        
        function mouseDownListener(evt) {
            var i;
            //We are going to pay attention to the layering order of the objects so that if a mouse down occurs over more than object,
            //only the topmost one will be dragged.
            var highestIndex = -1;
            
            //getting mouse position correctly, being mindful of resizing that may have occured in the browser:
            var bRect = theCanvas.getBoundingClientRect();
            mouseX = (evt.clientX - bRect.left)*(theCanvas.width/bRect.width);
            mouseY = (evt.clientY - bRect.top)*(theCanvas.height/bRect.height);
                    
            //find which shape was clicked
            for (i=0; i < numShapes; i++) {
                if  (hitTest(shapes[i], mouseX, mouseY)) {
                    dragging = true;
                    if (i > highestIndex) {
                        //We will pay attention to the point on the object where the mouse is "holding" the object:
                        dragHoldX = mouseX - shapes[i].x;
                        dragHoldY = mouseY - shapes[i].y;
                        highestIndex = i;
                        dragIndex = i;
                    }
                }
            }
            
            if (dragging) {
                window.addEventListener("mousemove", mouseMoveListener, false);
            }
            theCanvas.removeEventListener("mousedown", mouseDownListener, false);
            window.addEventListener("mouseup", mouseUpListener, false);
            
            //code below prevents the mouse down from having an effect on the main browser window:
            if (evt.preventDefault) {
                evt.preventDefault();
            } //standard
            else if (evt.returnValue) {
                evt.returnValue = false;
            } //older IE
            return false;
        }
        
        function mouseUpListener(evt) {
            theCanvas.addEventListener("mousedown", mouseDownListener, false);
            window.removeEventListener("mouseup", mouseUpListener, false);
            if (dragging) {
                dragging = false;
                window.removeEventListener("mousemove", mouseMoveListener, false);
                Settings.Fixation = parseInt(shapes[0].x) + ',' + parseInt(shapes[0].y);
                SpritzHelper.SaveFixation();
            }
        }
    
        function mouseMoveListener(evt) {
            var posX;
            var posY;
            var shapeRad = shapes[dragIndex].rad;
            var minX = shapeRad;
            var maxX = theCanvas.width - shapeRad;
            var minY = shapeRad;
            var maxY = theCanvas.height - shapeRad;
            //getting mouse position correctly 
            var bRect = theCanvas.getBoundingClientRect();
            mouseX = (evt.clientX - bRect.left)*(theCanvas.width/bRect.width);
            mouseY = (evt.clientY - bRect.top)*(theCanvas.height/bRect.height);
            
            //clamp x and y positions to prevent object from dragging outside of canvas
            posX = mouseX - dragHoldX;
            posX = (posX < minX) ? minX : ((posX > maxX) ? maxX : posX);
            posY = mouseY - dragHoldY;
            posY = (posY < minY) ? minY : ((posY > maxY) ? maxY : posY);
            
            shapes[dragIndex].x = posX;
            shapes[dragIndex].y = posY;
            
            drawScreen();
        }
        
        function hitTest(shape,mx,my) {
            
            var dx;
            var dy;
            dx = mx - shape.x;
            dy = my - shape.y;
            
            //a "hit" will be registered if the distance away from the center is less than the radius of the circular object        
            return (dx*dx + dy*dy < shape.rad*shape.rad);
        }
        
        function drawShapes() {
            context.fillStyle = shapes[0].color;
            context.beginPath();
            context.arc(shapes[0].x, shapes[0].y, shapes[0].rad, 0, 2*Math.PI, false);
            context.closePath();
            context.fill();
            context.lineWidth = 3;
            context.strokeStyle = '#D10000';
            context.stroke();
        }
        
        function drawScreen() {
            //bg
            context.fillStyle = "#000000";
            context.fillRect(0,0,theCanvas.width,theCanvas.height);
            
            drawShapes();       
        }
        
    }
};

SpritzHelper.ShowRSS = function (str) {
        if (str.length==0) {
            $('#newsStories').html('');
        return;
    }
    
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('#newsStories').html(xmlhttp.responseText);
            $('#newsStories').listview('refresh');
        }
    };
    
    xmlhttp.open("GET","getrss.php?q="+str,true);
    xmlhttp.send();
};



$(document).ready(function () {
    SpritzHelper.init();
});

//"speedItems": [25, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325, 350, 375, 400, 425, 450, 475, 500, 525, 550, 575, 600, 625, 650, 675, 700, 725, 750, 775, 800, 825, 850, 875, 900, 925, 950, 975, 1000],
