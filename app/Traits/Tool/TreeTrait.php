<?php

namespace App\Traits\Tool;

trait TreeTrait
{
	/**
	 * 递归成分类树
	 *
	 * @param mixed  $data
	 * @param string $idLabel
	 * @param string $pidLabel
	 * @param string $childrenLabel
	 * @return iterable
	 */
    public function tree(
        mixed $data,
        string $idLabel = 'id',
        string $pidLabel = 'pid',
        string $childrenLabel = 'children'
    ): iterable
    {
		$getChildren = function(&$node) use (&$getChildren, $data, $idLabel, $pidLabel, $childrenLabel) {
			$children = [];

			foreach ($data as $child) {
				if ($child[$pidLabel] == $node[$idLabel]) {
					$children[] = $getChildren($child);
				}
			}

			if (!empty($children)) {
				$node[$childrenLabel] = $children;
			}
			return $node;
		};

		$tree = [];
		foreach ($data as $item) {
			if ($item[$pidLabel] == 0) { // 如果是根节点，则添加到树中
				$tree[] = $getChildren($item);
			}
		}

        return $tree;
    }

	/**
	 * 所有子级
	 *
	 * @param array  $data
	 * @param mixed  $id
	 * @param array  $array
	 * @param string $idLabel
	 * @param string $pidLabel
	 * @return array
	 */
    public function childrenAll(
		array $data,
		mixed $id,
		array $array = [],
		string $idLabel = 'id',
		string $pidLabel = 'pid'
	): array
    {
        foreach ($data as $v) {
            if($v[$pidLabel] == $id){
                $array[] = $v;
                $array = $this->childrenAll($data, $v[$idLabel], $array);
            }
        }

        return $array;
    }

	/**
	 * 所有父级
	 *
	 * @param array  $data
	 * @param int    $id
	 * @param array  $array
	 * @param string $idLabel
	 * @param string $pidLabel
	 * @return array
	 */
    public function parentAll(
		array $data,
		int $id,
		array $array = [],
		string $idLabel = 'id',
		string $pidLabel = 'pid'
	): array
    {
        foreach ($data as $v) {
            if($v[$idLabel] === $id){
                $array[] = $v;
                $array = $this->parentAll($data, $v[$pidLabel], $array);
            }
        }

        return $array;
    }

    /**
     * 分类树去空
     *
     * @param array $arr
     * @param array $values
     * @return array
     */
    public function treeFilter(array $arr, array $values = ['', null, false, 0, '0',[]]): array
    {
        foreach ($arr as $k => $v) {
            if (is_array($v) && count($v)>0) {
                $arr[$k] = $this->treeFilter($v, $values);
            }
            foreach ($values as $value) {
                if ($v === $value) {
                    unset($arr[$k]);
                    break;
                }
            }
        }
        return $arr;
    }
}
