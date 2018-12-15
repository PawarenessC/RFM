<?php



namespace pawarenessc\RFM;



use pocketmine\event\Listener;

use pocketmine\plugin\PluginBase;



use pocketmine\item\Item;

use pocketmine\tile\Tile;

use pocketmine\tile\Chest as TileChest;

use pocketmine\tile\Sign;




use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\event\entity\EntityDamageEvent;

use pocketmine\event\player\PlayerPreLoginEvent;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\player\PlayerMoveEvent;



use pocketmine\Player;

use pocketmine\Server;

use pocketmine\scheduler\TaskScheduler;



use pocketmine\utils\textFormat;

use pocketmine\utils\Config;



use pocketmine\entity\Entity;

use pocketmine\entity\Effect;

use pocketmine\entity\EffectInstance;



use pocketmine\command\Command;

use pocketmine\command\CommandSender;



use pocketmine\level\Level;

use pocketmine\level\Position;

use pocketmine\level\particle\DustParticle;


use pocketmine\block\Block;

use pocketmine\math\Vector3;



use pocketmine\network\protocol\AddEntityPacket;

use pocketmine\network\mcpe\protocol\TransferPacket;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

use metowa1227\MoneySystemAPI\MoneySystemAPI;

use MixCoinSystem\MixCoinSystem;



class Main extends pluginBase implements Listener{









public function onEnable() {

 $this->getLogger()->info("=========================");
 $this->getLogger()->info("RFMを読み込みました");
 $this->getLogger()->info("二次配布,改造配布,譲渡を禁止します。");
 $this->getLogger()->info("また、使用する際はPawarenessCが作成したRFMを使用していることサーバー内で必ず明記してください。");
 $this->getLogger()->info("v0.1");
 $this->getLogger()->info("=========================");

 $this->system = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");

 $this->getServer()->getPluginManager()->registerEvents($this, $this);

 $this->totalminutes = 540;

 $this->minute = 540;

 $this->win = 0;

 $this->w = 0;

 $this->t = 0;

 $this->h = 0;

 $this->game = false;

 $this->namm = false;
 
$this->cogame = false;
	
	$this->pacm = false;

//$this->bigmap = false; 

 $this->em1 = false;

 $this->em2 = false;

 $this->tag = 1;

 $this->Server = $this->getServer();
 
 $this->guest = true;

 $this->ServerClass = new \ReflectionClass(get_class($this->Server));

 $this->l = "未発表";

 $this->kk = new Config($this->getDataFolder() ."player.yml", Config::YAML);

 $this->nige = new Config($this->getDataFolder() ."nige.yml", Config::YAML);
 
 $this->hunter = new Config($this->getDataFolder() . "hunter.yml", Config::YAML);
 
 $this->runner = new Config($this->getDataFolder() . "runner.yml", Config::YAML);
 
 $this->jaller = new Config($this->getDataFolder() . "jailer.yml", Config::YAML);

 $this->msg = new Config($this->getDataFolder()."Message.yml", Config::YAML,  

			[ 

			

			"INFO1" => "§bバスの下に隠れる行為はBAN対象です", 

 

			"INFO2" => "§b自首boxにイモる行為はBAN対象です", 



			"JISHUMSG" => "§b自首ができるようになったよ！", 



			"INFO4" => "頑張ろー", 

			

			"INFO5" => "自首ボックスで自首できます", 

			]); 
 
 $this->config = new Config($this->getDataFolder()."Setup.yml", Config::YAML, 



			[



			"説明" => "prizeでは単価の設定,pluginは経済プラグイン(EconomyAPI,MoneySystem,MixCoinSystem)の中から選べます",


			"prize" => "5",
			
			
			"plugin" => "MoneySystem",
			


			]);
			
			
	$this->xyz = new Config(
      $this->getDataFolder() . "xyz.yml", Config::YAML, array(
        "逃走者"=> array(
          "x"=>326,
          "y"=>4,
          "z"=>270,
        ),
        "牢屋"=> array(
          "x"=>305,
          "y"=>5,
          "z"=>331,
        ),
        "観戦"=> array(
          "x"=>255,
          "y"=>4,
          "z"=>255,
        ),
        "ハンター"=> array(
          "x"=>246,
          "y"=>4,
          "z"=>357,
        ),
        "ワールド"=>"world",
      )
    );


}





public function onQuit(PlayerQuitEvent $event){

 $h = $this->h;

 $t = $this->t;

 $p = $event->getPlayer();

 $name = $p->getName();
 
$this->hunter->remove($name, "", true);
$this->hunter->save();

$this->runner->remove($name, "", true);
$this->runner->save();

if($this->runner->exists($name)) {
   $this->t = $t - 1;
  
  }elseif($this->hunter->exists($name)) {

   $this->h = $h - 1;
  }

 }

	
 public function onMove(PlayerMoveEvent $event){

		$player = $event->getPlayer();

		$name = $player->getName();

	   if($this->hunter->exists($name)) {

		$level = $player->getLevel();

		$pos = new Vector3($player->getX(),$player->getY()+1,$player->getZ());

			$pt = new DustParticle($pos, mt_rand(), mt_rand(), mt_rand(), mt_rand());

			$count = 5;//回数

				for($i = 0;$i < $count; ++$i){

 				$level->addParticle($pt);

			}
	   }

	}



 public function onTouch(PlayerInteractEvent $event){

	$p = $event->getPlayer();

	$n = $p->getName();

	$H = $this->h;

        $T = $this->t;

	$b = $event->getBlock()->getId();
	 
	 $c = $event->getBlock();

	$xr = $c->getX();

	$yr = $c->getY();

	$zr = $c->getZ();

	$block = Block::get(0,0);

	$rs = new Vector3($xr, $yr, $zr);

	$level = $this->getServer()->getLevelByName($this->xyz->getAll()["ワールド"]);

    $win = $this->win;

    if($b == 121){

				if ($this->runner->exists($n)) {
            if($this->minute > 0){

                if($this->minute < 330){

                    $this->t = $this->t - 1;
                    $p->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§b自首をして".$win."円獲得！");

                    $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§b".$n."が自首を行い、§c{$win}§b円獲得した！！残り{$this->t}人");

                    $this->addMoney($n, $win);

                    $this->runner->remove($n, "", true);
					$this->runner->save();
                    $p->setGamemode(3);
					$xyz = new Vector3($this->xyz->getAll()["観戦"]["x"], $this->xyz->getAll()["観戦"]["y"], $this->xyz->getAll()["観戦"]["z"], $this->xyz->getAll()["ワールド"]);
                    $p->teleport($xyz);

                    }

                }

            }

    }elseif($b == 19){

        if($this->game == true){

            $p->teleport($pou);

            $p->sendMessage("観戦場所へ移動中...");

            $p->setGamemode(3);

        }else{

            $p->sendMessage("§c現在テレポートすることはできません");

        }

    }elseif($b == 247){

        if($this->minute > 50){

            if($this->minute < 420){
				$name = $p->getName();

                if(!$this->runner->exists($name)) {

                    $p->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§eアスレチッククリア！復活しました！");

                    $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§d".$n."が復活したよ〜");

                    if($H < 6){

                    if($H >= $T / 3){

                    $hunter = 'runner';

                    $this->team($p, $hunter);

                    }elseif($H < $T){

                    $hunter = 'hunter';

                    $this->team($p, $hunter);

                    }elseif($H === $T){

                    $hunter = 'runner';

                    $this->team($p, $hunter);}

		}else{

                    $this->t = $T + 1;

                    $p->setNameTag("");
                    
                    $hunter = 'runner';

                    $this->team($p, $hunter);

                    $p->removeAllEffects();

                    return true;

            }
				 $xyz = new Vector3($this->xyz->getAll()["逃走者"]["x"], $this->xyz->getAll()["逃走者"]["y"], $this->xyz->getAll()["逃走者"]["z"], $this->xyz->getAll()["ワールド"]);
                 $p->teleport($xyz);

                }else{

                    $p->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§e途中参加しました！");

                    $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§d".$n."が途中参加したよ〜");

                    $team = 'runner';

                    $this->team($p, $team);

                }

                $xyz = new Vector3($this->xyz->getAll()["逃走者"]["x"], $this->xyz->getAll()["逃走者"]["y"], $this->xyz->getAll()["逃走者"]["z"], $this->xyz->getAll()["ワールド"]);
                $p->teleport($xyz);

            }

        }

    }

}



public function EntityDamageEvent(EntityDamageEvent $event){

 if($event instanceof EntityDamageByEntityEvent){

  $entity = $event->getEntity();

  $player = $event->getDamager();

  $t = 410;

  $m = $this->minute;

   if($entity instanceof Player && $player instanceof Player){

    $ena = $entity->getName();
    
    $epna = $player->getName();

    $player->sendMessage("§a".$ena."");

     if($this->pacm == true){

      if ($this->hunter->exists($pna) && $this->runner->exists($ena)) {

       $entity->sendMessage("§l§d[PAC-MANmission]§r§bPAC-MANmissionで逃走者にやられました");

       $this->getServer()->broadcastMessage("[PAC-MANmission]§aハンターの§b".$ena."§aがミッションで捕まりました");

       $this->h = $this->h - 1;
       
       $this->hunter->remove($name, "", true);
	   $this->hunter->save();

       $xyz = new Vector3($this->xyz->getAll()["牢屋"]["x"], $this->xyz->getAll()["牢屋"]["y"], $this->xyz->getAll()["牢屋"]["z"], $this->xyz->getAll()["ワールド"]);
       $entity->teleport($xyz);

      }

     }else{
$pname = $player->getName();
$ename = $entity->getName();
     if($this->game == true){

      if($this->hunter->exists($pname)) {

          if($this->runner->exists($ename)) {

        if($m > 0){

         if($m < 420){

	  $pn = $player->getName();

	  $en = $entity->getName();

	  $player->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§b確保報酬として500円を手に入れた！");

	  $this->addMoney($pn, 500);

	  $entity->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§c".$pn."§bに確保された...");

	  $entity->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§bアスレチックをクリアして復活しよう。");

	  $entity->addTitle("§c捕まりました...", "", 20, 20, 20);

	  $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§a".$en."が確保された...");

	  $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§cハンター→".$pn."");

          $this->kk->set($pn,$this->kk->get($pn) + 1);

          $this->kk->save();
          
          $this->runner->remove($en, "", true);
		  $this->runner->save();

          $xyz = new Vector3($this->xyz->getAll()["牢屋"]["x"], $this->xyz->getAll()["牢屋"]["y"], $this->xyz->getAll()["牢屋"]["z"], $this->xyz->getAll()["ワールド"]);
          $entity->teleport($xyz);


         $this->t = $this->t - 1;

         }

       }

      }

     }

    }

   }

  }

 }


}
public function onJoin(PlayerJoinEvent $event){

 $player = $event->getPlayer();

 $player->setGamemode(0);

 $name = $player->getName();
 

 $event->getPlayer()->setAllowMovementCheats(true);

   if(!$this->kk->exists($name)){

    $this->kk->set($name, "0");

    $this->kk->save();

    }

     if(!$this->nige->exists($name)){

      $this->nige->set($name, "0");

      $this->nige->save();

    }
			

		}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) :bool{

 switch ($command->getName()){
  case "ts";
  $this->startMenu($sender);
  return true;
}
}

 public function scheduler(){

  $this->minute--;

  $t = $this->t;

  $h = $this->h;

  $tm = $this->totalminutes;

  $min = $this->minute;

  $total = 420;

  $eme = mt_rand(1, 10);

  $x = mt_rand(177, 182);

  $z = mt_rand(71, 170);

  $xt = mt_rand(77, 82);

  $zt = mt_rand(71, 170);

  $ms = mt_rand(1, 5);

  $main = Server::getInstance()->getLevelByName("world");

  $game = Server::getInstance()->getLevelByName("tousou");

  $seiyo = Server::getInstance()

  $w = mt_rand(1, 3);
  
  $rand = mt_rand(1,5);

  $ww = $this->w;

  $xs = mt_rand(96, 186);

  $zs = mt_rand(184, 186);

  $xts = mt_rand(69, 185);

  $zts = mt_rand(69, 71);
  
  $prize = $this->config->get("prize");

  $ms2 = mt_rand(1, 3);

  $post = new Vector3($this->xyz->getAll()["逃走者"]["x"], $this->xyz->getAll()["逃走者"]["y"], $this->xyz->getAll()["逃走者"]["z"], $this->xyz->getAll()["ワールド"]);

  $posh = new Vector3($this->xyz->getAll()["牢屋"]["x"], $this->xyz->getAll()["牢屋"]["y"], $this->xyz->getAll()["牢屋"]["z"], $this->xyz->getAll()["ワールド"]);

  $players = Server::getInstance()->getOnlinePlayers();

  $win = $this->win;
  
$init = $min;

$minutes = floor(($init / 60) % 60);

$seconds = $init % 60;





if($this->game === false){
 if($min >= $total){
foreach ($players as $player){
   $this->getServer()->broadcastPopup("".$min - $total."秒後に開始\n\n");
}
 }

  
   }

 if($min <= 420){

  if($min >= 0){

  $this->win = $win + $prize;

   $this->getServer()->broadcastPopup("残り{$minutes}分{$seconds}秒§a賞金§d".$win."§b円§r\n     §l§a逃走者§f ".$t."§a人 \n\n\n");

 foreach ($players as $p){

  if($t == 0){

   $name = $p->getName();

   $this->minute = -1;

   $p->addTitle("§cGAMEOVER", "", 20, 20, 20);

   $p->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§b逃走者がいなくなったのでゲームが終了したよ！");

   $p->sendMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§bハンターは§a{$win}§b円獲得！");

     $this->endGame();

    
    if ($this->hunter->exists($name)) {

     $n = $p->getName();

     $this->addMoney($n, $win);
   }

  }

 }

}
}

switch($min){
case 450: 
$world = $this->xyz->get("ワールド");
$this->getServer()->loadLevel($world);
		
case 420:

  $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§bゲームが開始された！鬼はダイヤ装備をしてるよ");

  $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§bハンターは10秒間動けません！");

  $this->game = true;

   foreach ($players as $player){

    $name = $player->getName();

    $player->setNameTag("");

     if($this->runner->exists($name)) {

      $player->teleport($post);

     }

     if ($this->hunter->exists($name)) {

      $player->teleport($pos);

      $player->setImmobile(true);

     }

}

 break;

 case 419:
 
  $this->game = true;
 break;

 case 410:
  foreach ($players as $player){

    $name = $player->getName();

    if($this->hunter->exists($name)) {

      $player->setImmobile(false);

    }

  }

  $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§r§bハンターが動けるようになりました");
 break;

 case 380:
  $msg = $this->msg->get("INFO1"); 
  $this->getServer()->broadcastMessage("[INFO]{$msg}");
  break;

 case 350:
  $msg = $this->msg->get("INFO2"); 
  $this->getServer()->broadcastMessage("[INFO]{$msg}");
  break;

 case 330:
  $msg = $this->msg->get("JISHUMSG");
  $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]{$msg}");
  break;

 case 290:
  $msg = $this->msg->get("INFO4");
  $this->getServer()->broadcastMessage("[INFO]{$msg}");
  break;

 case 260:
  $msg = $this->msg->get("INFO5");
  $this->getServer()->broadcastMessage("[INFO]{$msg}");
  break;

 case 250:
  if($ms == 1){

 $this->pacm = true;

 foreach($players as $p){

 $p->addTitle("§c~ミッション発令~", "", 20, 20, 20);}

 $this->getServer()->broadcastMessage("§a=====§bミッション発生§a=====");

  $this->getServer()->broadcastMessage("§c・PAC-MAN MISSION");

  $this->getServer()->broadcastMessage("§d説明:§e20秒間ハンターと逃走者の立場が逆になる。");

  $this->getServer()->broadcastMessage("§e逃走者はハンターを捕まえて、ハンターを減らせ！");

  $this->getServer()->broadcastMessage("§a===================");

}

 if($ms == 2){

  $this->namm = true;

  foreach($players as $p){

   $name = $p->getName();

   $p->setNameTag($name);

 $p->addTitle("§c~ミッション発令~", "", 20, 20, 20);

 $this->getServer()->broadcastMessage("§a=====§bミッション発生§a=====");

  $this->getServer()->broadcastMessage("§c・MY NAME VISIBLE");

  $this->getServer()->broadcastMessage("§d説明:§e今から30秒間全員のネームタグが見えてしまうぞ");

  $this->getServer()->broadcastMessage("§eハンターから逃げやすく、逃走者を見つけやすくなってしまった！");

  $this->getServer()->broadcastMessage("§a===================");

  }

 }

 if($ms == 3){

 $this->getServer()->broadcastMessage("§b今回はミッションが発令しませんでした...");

  }

  break;

 case 230:
  if($this->pacm == true){

   $this->pacm = false;

   $this->getServer()->broadcastMessage("§aミッション終了");

   $this->getServer()->broadcastMessage("§c立場が元に戻ったぞ");

 }elseif($this->namm == true){

  $this->getServer()->broadcastMessage("§aミッション終了");

  $this->getServer()->broadcastMessage("§cネームタグが消えたぞ");

  foreach($players as $p){

   $p->setNameTag("");

}

}

 break;

 case 350:

 case 300:

 case 250:

 case 200:

 case 100:

 case 50:
  $this->getServer()->broadcastMessage("§a===§c途中結果発表§a===");

  $this->getServer()->broadcastMessage("残り".$t."人。生き残っているものは以下だ");

   foreach ($players as $player){

    $name = $player->getName();

     if($this->runner->exists($name)) {

      $this->getServer()->broadcastMessage("§a=§b".$name."§a=");

     }

   }

  $this->getServer()->broadcastMessage("§a========================");
  break;

 case 150:
  if($ms2 == 1){

  $this->em1 = true;

  foreach($players as $p){

      $p->addEffect(new EffectInstance(Effect::getEffect(2), 600, 3, false));

 $p->addTitle("§c~ミッション発令~", "", 20, 20, 20);

 $this->getServer()->broadcastMessage("§a=====§bエフェクトミッション発生§a=====");

  $this->getServer()->broadcastMessage("§c・足があああ");

  $this->getServer()->broadcastMessage("§d説明:§e今から30秒の間、足が遅くなるぞ！");

  $this->getServer()->broadcastMessage("§eslowな逃走中に貴方はイライラせずに居られるか！？");

  $this->getServer()->broadcastMessage("§a===================");

   break;

  }

  }

   if($ms2 == 2){

  $this->em2 = true;

  foreach($players as $p){
	$name = $p->getName();

   if($this->runner->exists($name)) {

   $p->addEffect(new EffectInstance(Effect::getEffect(14), 200, 3, false));

  }

  $p->addTitle("§c~ミッション発令~", "", 20, 20, 20);

 $this->getServer()->broadcastMessage("§a=====§bミッション発生§a=====");

  $this->getServer()->broadcastMessage("§c・ハンターが....見えない！？");

  $this->getServer()->broadcastMessage("§d説明:§e今から10秒間、ハンターが見えなくなるぞ！");

  $this->getServer()->broadcastMessage("§e果たして目に見えないハンターから逃げ切れるか！？");

  $this->getServer()->broadcastMessage("§a===================");

  break;

  }

   }

  if($ms2 == 3){

  $this->getServer()->broadcastMessage("§b今回はミッションが発生しませんでした...");

  break;

}
 break;

 case 140:
  if($this->em2 == true){

  $this->getServer()->broadcastMessage("§aミッション終了");

  $this->getServer()->broadcastMessage("§cハンターが見えるようになったぞ");
}
  break;

 

 case 120:
  if($this->em1 == true){

  $this->getServer()->broadcastMessage("§aミッション終了");

  $this->getServer()->broadcastMessage("§c足が元に戻ったぞ");

}

  break;

 

 case 50:
  $this->getServer()->broadcastMessage("[TIPS]§a残り50秒。復活できなくなったぞ");
 break;

 case 20:
  foreach($players as $p){
  if($rand === 1){
  $this->getServer()->broadcastMessage("[TIPS]§a残り20秒！名前が見えるようになったよ！");
   $itemhel = $p->getArmorInventory()->getHelmet();
   $id = $itemhel->getId();
   if($this->runner->exists($name)) {
   $p->setNameTag($p->getName());
   }
  }else{
  $this->getServer()->broadcastMessage("[TIPS]§a残り20秒！頑張って！");
  }
  }
  break;
  
 case 3;
 foreach($players as $p){

 $p->addTitle("3", "", 20, 20, 20);}
 break;

 

 case 2;
 foreach($players as $p){

 $p->addTitle("2", "", 20, 20, 20);}
 break;

 

 case 1;
 foreach($players as $p){

 $p->addTitle("1", "", 20, 20, 20);}
 break;

 

 case 0:
  $this->getServer()->broadcastMessage("§a===§c結果発表§a===");

  $this->getServer()->broadcastMessage("[NOTICE]§r§bゲームが終了しました。生き残ったのは".$t."人。確保されなかった人たち↓");

   foreach ($players as $player){

    $player->addTitle("§6Congratulations!", "", 20, 20, 20);

    $name = $player->getName();
    
    $this->hunter->remove($name, "", true);
	$this->hunter->save();

	$this->runner->remove($name, "", true);
	$this->runner->save();

    if($this->runner->exists($name)){

      $this->getServer()->broadcastMessage("§a===§b".$name."§a===");

      $player->sendMessage("[囚人]よく逃げ切ったな。");

      $player->sendMessage("[囚人]逃げ切った報酬として".$win."円やるわ！受け取れい！");

      //$this->getServer()->getPluginManager()->getPlugin("円")->addmoney($name, $win);

      $this->addMoney($name, $win);

      $this->nige->set($name,$this->nige->get($name) + 1);

      $this->nige->save();
      $this->endGame();
      $this->getServer()->broadcastMessage("§a========================");

      break;

     }

   }

}
}

 public function team($player, $team){
$name = $player->getName();
  if($team == 'runner'){

   $this->runner->set($name, "", true);
	$this->runner->save();

   $t = $this->t;

   $this->t = $t + 1;

   $player->setNameTagVisible(false);

   $player->sendMessage("[NOTICE]あなたは§b逃走者");


   $player->removeAllEffects();

  }elseif($team == 'hunter'){

   $this->hunter->set($name, "", true);
   $this->hunter->save();

   $h = $this->h;

   $this->h = $h + 1;

   $player->setNameTagVisible(false);

   $player->sendMessage("[NOTICE]あなたは§cハンター");
   $player->addEffect(new EffectInstance(Effect::getEffect(1), 114514, 1, false));

  }

  return true;

 }

   public function getNige($name){

     if($this->nige->exists($name)){

     return $this->nige->get($name);

     }else{

         $this->nige->set($name,"0");

         $this->nige->save();

          return 0;

     }

   }

   public function getKakuho($name){

     if($this->kk->exists($name)){

     return $this->kk->get($name);

     }else{

         $this->kk->set($name,"0");

         $this->kk->save();

          return 0;

          

     }

   }
   public function endGame(){
   $this->totalminutes = 540;
   $this->minute = 540;
   $this->redm = false;
   $this->emem = false;
   $this->pacm = false;
   $this->win = 0;
   $this->w = 0;
   $this->t = 0;
   $this->h = 0;
   $this->game = false;
   $this->namm = false;
   $this->cogame = false; 
   $this->em1 = false;
   $this->em2 = false;
   $this->tag = 1;
   $this->l = "未発表";
   $scheduler = $this->getScheduler();
   $scheduler->cancelAllTasks();
   foreach(Server::getInstance()->getOnlinePlayers() as $player){
   $level = $this->getServer()->getDefaultLevel();
   $name = $player->getName();
   $player->setImmobile(false);
   $this->runner->remove($name, "", true);
	 $this->runner->save();
	  $this->hunter->remove($name, "", true);
	  $this->hunter->save();
   $player->teleport($level->getSafeSpawn());
   $player->setGamemode(0);
   $player->setNameTag($player->getDisplayName());

   }
  }
        
   public function startMenu($player) {
    
        $name = $player->getName();
        $buttons[] = [ 
        'text' => "§l§b逃走中に参加する", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //0
        $buttons[] = [ 
        'text' => "§l§e逃走中から抜ける", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //1
        $buttons[] = [ 
        'text' => "§l§eステータスを確認する", 
        'image' => [ 'type' => 'path', 'data' => "" ] 
        ]; //2
        $this->sendForm($player,"TagGame","§a選択してください",$buttons,1145145);
        $this->info[$name] = "form";
        }
        
        public function sendForm(Player $player, $title, $come, $buttons, $id) {
  $pk = new ModalFormRequestPacket(); 
  $pk->formId = $id;
  $this->pdata[$pk->formId] = $player;
  $data = [ 
  'type'    => 'form', 
  'title'   => $title, 
  'content' => $come, 
  'buttons' => $buttons 
  ]; 
  $pk->formData = json_encode( $data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE );
  $player->dataPacket($pk);
  $this->lastFormData[$player->getName()] = $data;
  }
  
  
  public function onPrecessing(DataPacketReceiveEvent $event){



  $player = $event->getPlayer();

  $pk = $event->getPacket();

  $name = $player->getName();

    if($pk->getName() == "ModalFormResponsePacket"){

      $data = $pk->formData;

      $result = json_decode($data);

          if($data == "null\n"){

      }else{
	    
	    switch($pk->formId){

			case 1145145://プレイヤー用
			if($data == 0){//参加する
			 $H = $this->h;

    $T = $this->t;
    
    $all = $H + $T;

    $player->setNameTag("");
    
    $name = $player->getName();

     if($this->runner->exists($name)) {

      $player->sendMessage("[逃走中]§c既に参加しています");

     }elseif($this->hunter->exists($name)) {

      $player->sendMessage("[逃走中]§c既に参加しています");

     }else{
  	
  	
  	if($this->game == false){
  	 if($this->cogame == false){
  	  if($all == 0){
  	   $this->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "scheduler"]), 20);
  	   $this->cogame = true;
       $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§b逃走中を開催します.../tsで参加しましょう！");
        $hunter = 'runner';
      $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§e{$name}さんが逃走中に参加しました");
}
}else{
if($H < 10){

     if($H >= $T / 3){

      $hunter = 'runner';
      $this->team($player, $hunter);
      $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§e{$name}さんが逃走中に参加しました");
     }elseif($H < $T){

      $hunter = 'hunter';
      $this->team($player, $hunter);
      $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§c{$name}さんが逃走中に参加しました");
      $player->addEffect(new EffectInstance(Effect::getEffect(1), 114514, 1, false));
     }elseif($H === $T){

      $hunter = 'runner';
      $this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§e{$name}さんが逃走中に参加しました");
      $this->team($player, $hunter);
			}

		}else{

 $hunter = 'runner';
 $this->team($player, $hunter);
$this->getServer()->broadcastMessage("§l[§aS§eY§6S§bT§cE§4M§f]§e{$name}さんが逃走中に参加しました");
  }

  }
  }
  }
  	if($this->game === true){

			$player->sendMessage("[逃走中]§r§b現在試合中です、途中参加するか、観戦をしてお楽しみください。");

			$player->addTitle("§cError", "試合中", 20, 20, 20);

}
break;
   }elseif($data == 1){//抜ける
 	       $name = $player->getName();
 	       $level = $this->getServer()->getDefaultLevel();
       $H = $this->h;
       $T = $this->t;
       $player->setGamemode(0);
     if($this->runner->exists($name)) {

      $player->sendMessage("[逃走中]§c逃走中を抜けました");
      $this->getServer()->broadcastMessage("[逃走中]§e{$name}さんが逃走中を抜けました");
	 $this->t = $T - 1;
	 $player->teleport($level->getSafeSpawn());
	 $this->runner->remove($name, "", true);
	 $this->runner->save();
     }elseif($this->hunter->exists($name)) {

      $player->sendMessage("[逃走中]§c逃走中を抜けました");
      $this->getServer()->broadcastMessage("[逃走中]§c{$name}さんが逃走中を抜けました");
	  $this->h = $H - 1;
	  $player->teleport($level->getSafeSpawn());
	  $this->hunter->remove($name, "", true);
	  $this->hunter->save();
     }else{
     
     $player->sendMessage("[逃走中]§c逃走中を抜けました");
     $player->teleport($level->getSafeSpawn());
     
     }
     break;
     }elseif($data == 2){
      $name = $player->getName();
      $data = [
				"type" => "custom_form",
				"title" => "§b{$name}さんのステータス",
				"content" => [
					[
						"type" => "label",
						"text" => "§b逃げ切った回数: §e{$this->getNige($name)}§b回"
					],
					[
						"type" => "label",
						"text" => "§d確保した回数: §e{$this->getKakuho($name)}§d回"
					]
				]
			];
			$this->createWindow($player, $data, 4925389);
     }
     break;
     
}
 }
}
}
	public function createWindow(Player $player, $data, int $id){
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
		$player->dataPacket($pk);
	}
	
	public function addMoney($name, $money){
 	$plugin = $this->config->get("plugin");
 	if($plugin == "MoneySystem"){
 	 MoneySystemAPI::getInstance()->SetMoneyByName($name, $money);
 	}elseif($plugin == "EconomyAPI"){
 	  $this->system->addmoney($name ,$money);
 	}elseif($plugin == "MixCoinSystem"){
 	 MixCoinSystem::getInstance()->PlusCoin($name,$Coin);
 	}
 	return true;
 
 }
 }
