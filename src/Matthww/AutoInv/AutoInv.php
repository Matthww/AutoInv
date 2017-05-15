<?php

namespace Matthww\AutoInv;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class AutoInv extends PluginBase implements Listener {

    private $drop;

    public function onEnable()
    {
        $serverName = $this->getServer()->getName();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if($serverName === "PMMP" or $serverName === "PocketMine-MP")
        {
            $this->getLogger()->info("is enabled!");
        }
        else{
            $this->getLogger()->warning("This plugin is not tested for " . $serverName);
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("is disabled!");
    }

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();

        foreach($event->getDrops() as $this->drop)
        {
            if($this->isInventoryFull($player) == true)
            {
                $player->addTitle("§cYour inventory is full!", " ", 5, 40, 5);
            } else {
                if($player->getGamemode() == 0)
                {
                    if($event->isCancelled() == false)
                    {
                        $player->getInventory()->addItem($this->drop);
                        $event->setDrops([]);
                    }
                }
            }
        }
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isInventoryFull(Player $player)
    {
        $inventory = $player->getInventory();

        if ($inventory->canAddItem($this->drop))
        {
            return false;
        }
        return true;
    }
}
