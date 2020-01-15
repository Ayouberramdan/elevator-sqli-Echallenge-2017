<?php

namespace Sqli;

/**
 * Class Building
 * @package Sqli
 */
class Building
{
    private $nbOfFloors; //Number of floors in the  builing
    private $elevators; //List of elevators

    /**
     * Building constructor.
     * @param int $nbFloors
     * @param array $elevators
     */
    function __construct($nbFloors, array $elevators)
    {
        $this->nbOfFloors = $nbFloors;
        $this->elevators = $elevators;
    }

    /**
     * Request the elevator
     *
     * @param int $floor
     * @return Elevator
     */
    public function requestElevator(int $floor = 0): ?Elevator
    {
        $from = $min = $this->nbOfFloors;
        if ($floor != 0) {
            $from = $floor;
        }
        $closestElevator = null;

        //This set is served to store elevators by their state
        $set = array(
            "READY" => [],
            "UP" => [],
            "STOPPED" => [],
            "DOWN" => []
        );
        if (!empty($this->elevators)) {
            foreach ($this->elevators as $elevator) {
                $set[$elevator->getState()][] = $elevator;

            }
        }

        //Sorting the Elevator that are ready (have not been stopped) from the closest to the farther
        usort($set["READY"], function ($e1, $e2) use ($from) {
            return ($from - $e1->getCurrentFloor()) > ($from - $e2->getCurrentFloor());
        });

        //Sorting the Elevator that are moving asc from the closest to the farther
        usort($set["UP"], function ($e1, $e2) use ($from) {
            return ($from - $e1->getCurrentFloor()) > ($from - $e2->getCurrentFloor());
        });

        //Sorting the Elevator that have been stopped during the movement
        usort($set["STOPPED"], function ($e1, $e2) use ($from) {
            return ($from - $e1->getCurrentFloor()) > ($from - $e2->getCurrentFloor());
        });

        //Sorting the Elevators that are moving  down
        usort($set["DOWN"], function ($e1, $e2) use ($from) {
            return (2 * $from - $e1->getCurrentFloor()) > (2 * $from - $e2->getCurrentFloor());
        });

        if (isset($set["READY"][0])) { //We prioritize the elevators that are ready and we take the first one
            return $set["READY"][0];
        } elseif ($set["UP"][0]) {//If no ready elevator picked up, we prioritize the elevators that are moving up and we take the first one
            return $set["UP"][0];
        } elseif ($set["STOPPED"][0]) {//If no moving up elevator picked up, we prioritize the elevators that have been stopped and we take the first one
            return $set["STOPPED"][0];
        } elseif (isset($set["DOWN"][0])) { //picked the closest one from moving down elevator.
            return $set["DOWN"][0];
        } else {
            return null;
        }

    }

    /**
     * Finds an elevator
     *
     * @param string $id
     * @return Elevator
     */
    public function find(string $id)
    {
        for ($i = 0; $i < count($this->elevators); $i++) {
            if (strcmp($id, $this->elevators[$i]->getElevatorId()) === 0) {
                return $this->elevators[$i];
            }
        }
        return null;
    }

    /**
     * Move the given elevator
     *
     * @param string $id
     * @param string $state
     */
    public function move(string $id, string $state): void
    {
        $elevator = $this->find($id);
        if (!is_null($elevator)) {
            $elevator->setState($state);
        }
    }

    /**
     * Add new elevator to the building
     *
     * @param string $id
     * @param int $floor
     * @return $this
     */
    public function add(string $id, int $floor)
    {
        $exists = is_null($this->find($id));
        if ($exists == true) {
            $this->elevators[] = new Elevator($id, $floor);
        }
        return $this;
    }

    /**
     * Stop the elevator at the given floor
     *
     * @param string $id
     * @param int $floor
     */
    public function stopAt(string $id, int $floor)
    {
        $elevator = $this->find($id);
        if (!is_null($elevator)) {
            $elevator->setCurrentFloor($floor);
            $elevator->setState("STOPPED"); //Mark the  elevator as stopped
        }
    }

    /**
     * @param array $elevators
     */
    public function setElevators(array $elevators): void
    {
        $this->elevators = $elevators;
    }

}