

<!-- Make sure it looks pretty :)-->
<style>
.center404 { text-align: center;  }
.li404 { line-height: 2em; padding-left:10px;}
.background404 { background-color: #f9f9f9;
padding:10px;
    border-style: solid;
    border-color: #5c5c5c;
    border-radius: 12px;}

</style>
<?php

//PHP array containing different 404 lines.
$random404 = array(
    'Yikees! how did you even get here dude',
    'Sometimes, the princess is in another castle',
    'I feel like you did this on purpose',
    'Are you... Wait... no.. what?!',
    "FallenFlash sent you here didn't he!",
    'You action has been logged. (nak jk, just a wrong page)',
    'Whoops! 404!',
    'This is not the droid you are looking for **wave hand**',
    'Dave probably made you come here to look at his awesome art',
    'Did RedFirebreak mention that he tested this page too? Show-off',
    'So. About that missing page',
    'Ding dong, the page is wrong',
    'Did you expect something? Are we lacking features?',
    'Whoops.',
    'owa wamu shindeiru,',
    '<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">You can click here</a>',
);

//Generate a random line.
$randomdisplay404 = $random404[mt_rand(0, sizeof($random404) - 1)];
?>

<!-- Page is starting here-->
<div class="container d-flex flex-column align-items-center justify-content-around">
    <div class="row p-4 text-center">
        <h1 class="center404"><?=$randomdisplay404?></h1>
</div><div class="row text-center">
        <p class="center404">You should probably look elsewhere, cause this page does not exist. Click the shiny button to go to the homepage<br>
        So, lets talk about how you got here</p>
</div>
<div class="row">
            <div class="background404">
                <h4>So, the most likely reason that you came here is because:</h3>
                <ul>
                <li class="li404">The page has moved</li>
                <li class="li404">The page no longer exist</li>
                <li class="li404">You were looking for that awesome puppy which is still hidden within these files</li>
                <li class="li404">You just wanted to know if spotamon had a custom 404 page, we do.</li>
                <ul>
            </div>
</div><br>
<div class="row p-2">
                <a href="/index.php" on-click="goBack();return flase;" class="my- 2 btn btn-info m-auto" role="button">Back home</a>
</div>
        <img class="center404" style="border-radius: 50%;" src="\core\assets\img\404stopsign.jpg" alt="404">
    </div>
</div>
<script>
function goBack() {
    window.history.back();
}
</script>
<!-- Page-end -->