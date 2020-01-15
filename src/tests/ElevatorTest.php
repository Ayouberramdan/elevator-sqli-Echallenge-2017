<?php
declare(strict_types=1);

namespace Sqli\Elevator;

use PHPUnit\Framework\TestCase;
use Sqli\Building;
use Sqli\Elevator;

class ElevatorTest extends TestCase
{
    private $building;

    public function provider1()
    {
        return array(
            array(
                array(new Elevator("id1", 1), new Elevator("id2", 6))
            )
        );
    }

    public function provider2()
    {
        return array(
            array(
                array(new Elevator("id1", 1), new Elevator("id2", 6), new Elevator("id3", 5))
            )
        );
    }


    function setUp(): void
    {
        $this->building = new Building(10, []);
    }

    /**
     * @dataProvider  provider1
     * matching the_closest_elevator_to_top_floor_should_arrive_first()
     * @param array $elevators
     */
    public function theClosestElevatorToTopFloorShouldArriveFirst(array $elevators)
    {
        $this->building->setElevators($elevators);
        $closest = $this->building
            ->requestElevator();
        $this->assertTrue("id2" === $closest->getElevatorId());
    }

    /**
     * @dataProvider  provider1
     * matching elevators_going_down_arrive_last_to_top_floor()
     * @param array $elevators
     */
    public function testElevatorsGoingDownArriveLastToTopFloor(array $elevators)
    {
        $this->building->setElevators($elevators);
        $this->building->move("id2", "DOWN");
        $closest = $this->building
            ->requestElevator();
        $this->assertTrue("id1" === $closest->getElevatorId());
    }

    /**
     * @dataProvider  provider1
     * matching elevators_going_up_should_arrive_to_top_floor_before_those_going_down()
     * @param array $elevators
     */
    public function testElevatorsGoingUpShouldArriveToTopFloorBeforeThoseGoingDown(array $elevators)
    {
        $this->building->setElevators($elevators);
        $this->building
            ->move("id1", "UP");
        $this->building
            ->move("id2", "DOWN");
        $closest = $this->building
            ->requestElevator();
        $this->assertTrue("id1" === $closest->getElevatorId());
    }

    /**
     * @dataProvider  provider2
     * matching elevators_going_up_should_be_compared_to_those_resting()
     * @param array $elevators
     */
    public function testElevatorsGoingUpShouldBeComparedToThoseResting(array $elevators)
    {
        $this->building->setElevators($elevators);
        $this->building
            ->move("id1", "UP");
        $this->building
            ->move("id2", "DOWN");
        $closest = $this->building
            ->requestElevator();
        $this->assertTrue("id3" === $closest->getElevatorId());
    }

    /**
     * @dataProvider  provider2
     * matching elevators_going_up_and_not_stopping_should_arrive_first_to_top_floor()
     * @param array $elevators
     */
    public function testElevatorsGoingUpAndNotStoppingShouldArriveFirstToTopFloor(array $elevators)
    {
        $this->building->setElevators($elevators);
        $this->building
            ->move("id1", "UP");
        $this->building
            ->move("id2", "DOWN");
        $this->building
            ->move("id3", "UP");
        $this->building
            ->stopAt("id3", 7);
        $closest = $this->building
            ->requestElevator();
        $this->assertTrue("id1" === $closest->getElevatorId());
    }

    /**
     * @dataProvider  provider1
     * matching can_request_elevator_in_middle_of_building()
     * @param array $elevators
     */
    public function testCanRequestElevatorInMiddleOfBuilding(array $elevators)
    {
        $this->building->setElevators($elevators);
        $closest = $this->building
            ->requestElevator(5);
        $this->assertTrue("id2" === $closest->getElevatorId());
    }
}