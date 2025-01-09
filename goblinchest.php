<?php
# Goblin Chest by Robert
# 23July2006

function goblinchest_getmoduleinfo(){
	$info = array(
		"name"=>"Goblin Chest",
		"version"=>"1.0",
		"author"=>"`2Robert",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
			"Goblin Chest - Settings,title",
			"gems"=>"How many gems to find in the chest?,range,1,10,1|1",
			"mingold"=>"Minimum amount of gold in chest?,range,1,25,1|10",
			"maxgold"=>"Maximum amount of gold in chest?,range,5,100,5|25",
		)
	);
	return $info;
}

function goblinchest_install(){
	if (!is_module_active('goblinchest')){
		output("`^ Installing Goblin Chest - forest event `n`0");
	}else{
		output("`^ Up Dating Goblin Chest - forest event `n`0");
	}
	module_addeventhook("forest","return 100;");
	return true;
}

function goblinchest_uninstall(){
	output("`^ Un-Installing Goblin Chest - forest event `n`0");
	return true;
}

function goblinchest_runevent($type){
	global $session;
	$from = "forest.php?";
	$op = httpget('op');
	$gems=get_module_setting("gems");
	$min=get_module_setting("mingold");
	$max=get_module_setting("maxgold");
	$session['user']['specialinc'] = "module:goblinchest";

	if ($op=="" || $op=="search"){
		output("`n`2 You come upon two `@Goblins`2, each with a `&wooden club`2 in their hand. ");
		output("`n`n They are beating each other over the head. ");
		output("`n`n`6 Open the Chest and take the treasure`2, one `@Goblin`2 shouts. ");
		output("`n`n`6 No dont open it`2,`6 there is evil in there`2, the other one shouts. ");
		output("`n`n ...the `@Goblins `2continue to beat each other over the head. ");
		output("`n`n A `& wooden chest `2 is at your feet. ");
		output("`n`n`& What will you do? ");
		addnav("Goblin Chest");
		addnav("(O) Open the Chest", $from."op=open");
		addnav("(R) Return to forest", $from."op=dont");
	}elseif ($op=="open"){
		$session['user']['specialinc'] = "";
		output("`n`n`2 You decide to open the chest and ...");
		switch(e_rand(1,12)){
			case 1: case 7:
			output("`n`n find inside a small pouch filled with colorful stones and %s gem. ",$gems);
			debuglog("found `^ $gems gem `0 in the goblins chest ");
			$session['user']['gems']+=$gems;
			break;
			case 2: case 8:
			output("`n`n find inside a small pouch filled with colorful stones and 1 gold coin. ");
			debuglog("found 1 gold coin in the goblins chest ");
			$session['user']['gold']+=1;
			break;
			case 3: case 9:
			output("`n`n out comes a `7 dark cloud `2 which hovers over you ...you dont feel so good. ");
			$darkcloud = array(
				"name"=>"`7Dark Cloud",
				"rounds"=>10,
				"defmod"=>0.8,
				"atkmod"=>0.9,
				"wearoff"=> "You feel much better as the Dark Cloud dissipates.",
				"roundmsg"=>"The Goblin Dark Cloud makes you very weak",
				"schema"=>"module-goblinchest",
			);
			apply_buff('goblinchest',$darkcloud);
			break;
			case 4: case 10:
			output("`n`n find inside a small pouch filled with colorful stones and %s gold coins. ",$min);
			debuglog("found $min gold coin in the goblins chest ");
			$session['user']['gold']+=$min;
			break;
			case 5: case 11:
			output("`n`n out jumps a `@Goblin `2 who bonks you over the head,`n`n all three `@Goblins `2 break out in laughter and run away. ");
			$session['user']['hitpoints'] = round($session['user']['hitpoints']*.95);
			break;
			case 6: case 12:
			output("`n`n find inside a small pouch filled with colorful stones and %s gold coins. ",$max);
			debuglog("found $max gold coin in the goblins chest ");
			$session['user']['gold']+=$max;
			break;
		}
	}else{
		output("`n`n`2 Not wishing to partake in any `@Goblin `2shenanigan, you leave them and continue your adventure. ");
		$session['user']['specialinc'] = "";
	}
}

function goblinchest_run(){
}
?>
