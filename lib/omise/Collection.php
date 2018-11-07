<?php
namespace Omise;

class Collection implements \Countable
{
    /**
     * @var string|null
     */
    protected $from;
    protected $to;

    /**
     * @var int|null
     */
    protected $offset;
    protected $limit;

    /**
     * @var int|null  of a total amount of items.
     */
    protected $total;

    /**
     * @var string|null
     */
    protected $order;

    /**
     * @var string|null
     */
    protected $location;

    /**
     * @var array|null
     */
    protected $items;

    public static function collect($items)
    {
        $collection = new static;
        $collection->build($items);

        return $collection;
    }

    public function build($items)
    {
        $this->from     = $items['from'];
        $this->to       = $items['to'];
        $this->offset   = $items['offset'];
        $this->limit    = $items['limit'];
        $this->total    = $items['total'];
        $this->order    = $items['order'];
        $this->location = $items['location'];

        foreach ($items['data'] as $item) {
            $this->items[] = \OmiseCharge::retrieve($item['id']);
        }
    }

    public function count()
    {
        return $this->total;
    }
}
