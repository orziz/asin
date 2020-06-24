<?php

namespace PHF;

class StopWatch
{
    public function start()
    {
        list($usec, $sec) = explode(" ", microtime());

        $this->start = (float) $sec + (float) $usec;
    }

    public function stop()
    {
        list($usec, $sec) = explode(" ", microtime());

        $this->end = (float) $sec + (float) $usec;
    }

    public function duration()
    {
        $duration = (int) (($this->end - $this->start) * 1000);

        return $duration;
    }

    private $start = 0;
    private $end   = 0;
}
