<?php
require_once 'initiate.php';

$query = "SELECT gid, gname, glatitude, glongitude, gteam, type, tname, actraid, actboss, hour, min, ampm, egg, monster, raidby, exraid, exraiddate, eggby, teamby, rcp FROM gyms as g LEFT JOIN teams AS t ON g.gteam = t.tid LEFT JOIN pokedex AS p ON p.id = g.actboss LEFT JOIN raidbosses AS r ON r.rid = g.actboss WHERE (g.gteam = t.tid AND g.egg=0 AND g.actraid=0) OR (g.gteam = t.tid AND p.id=g.actboss AND r.rid = g.actboss) OR (g.gteam = t.tid AND g.egg!=0)";
$result = mysqli_query($conn,$query)or die(mysqli_error($conn));
//////////////////// MAP XML \\\\\\\\\\\\\\\\\\\\\

header('Content-Type: text/xml');

// Start XML file, echo parent node
echo "<?xml version='1.0' encoding='utf-8'?>";
echo "<markers>";

$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node ?>
<marker gid="<?=$row['gid']?>" gname="<?=htmlentities($row['gname'], ENT_XML1|ENT_QUOTES)?>"
        glatitude="<?=$row['glatitude']?>" glongitude="<?=$row['glongitude']?>"
        gteam="<?=$row['gteam']?>" type="<?=$row['type']?>" tid="<?=$row['tname']?>"
        actraid="<?=$row['actraid']?>" actboss="<?=$row['actboss']?>" hour="<?=$row['hour']?>"
        min="<?=$row['min']?>" ampm="<?=$row['ampm']?>" egg="<?=$row['egg']?>"
        bossname="<?=$row['monster']?>" raidby="<?=$row['raidby']?>"
        eggby="<?=$row['eggby']?>" teamby="<?=$row['teamby']?>" bosscp="<?=$row['rcp']?>"
        exraid="<?=$row['exraid']?>" exraiddate="<?=$row['exraiddate']?>" />
        <?php
  $ind = $ind + 1;
}

// End XML file ?>
</markers>

