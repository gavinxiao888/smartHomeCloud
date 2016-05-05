<?php
/*
 *该文件是用来操作XML格式文件
 */

class xml
{
    /*
     * @power 该函数用来读取有一对父子节点的XML，只有一个根源素和一个子元素
     * @param $path为文件位置;$parent为父节点;$child为子节点
     * @return array一维数组
     * @todo 没有考虑到重构的想法。有很多代码段可以分离出来做一个函数
     */
    public function read_1node($path, $parent, $child)
    {
        $xml = new DOMDocument();
        $xml->load($path);

        $main = $xml->getElementsByTagName($parent);
        $ay = array();

        foreach( $main as $main)
        {
            $list = $main->getElementsByTagName($child);
            foreach ( $list as $list )
            {
                $value = $list->firstChild->nodeValue;
                array_push($ay, $value);
            }

        }
        unset($xml);//销毁变量
        return $ay;
    }
    /*
     * @power 追加节点。适用于只有一个节点的XML
     * @param $path文件位置;$root总节点;$child要添加的子节点;$child_value 子节点的值
     * @return 1成功 0失败
     */
    public function add_1node($path, $child, $child_value)
    {
            $xml = new DOMDocument();//建立实例
            $xml -> formatOutput = true;

           try
            {
                $xml -> load($path);
                $child = $xml -> createElement($child);//创建节点
                $value = $xml -> createTextNode($child_value);//设置值

                $root = $xml -> documentElement;//得到根节点

                $root -> appendChild($child);//添加节点
                $child -> appendChild($value);//添加值

                if( $xml -> save($path) ==1 )//保存文件
                {
                    unset($xml);
                    return 1;
                }
                return 0;
            }
           catch(Exception $e)
           {
               Log::error('人为错误:操作xml时；时间:'.time().'代码'.__FILE__.__LINE__.';');
               return 0;
           }
    }
    /*
     * @power 修改一个值。适用于一个子节点的xml。只会修改一个值
     * @param $path文件位置;$child为子节点;$old_child_value为旧值;$new_child_value为新值
     * @return 0 ：失败 1：成功
     * @先查找在修改
     */
    public function edit_1node($path, $child, $old_child_value, $new_child_value)
    {
        $xml = new DOMDocument();
        try
        {
            $xml->load($path);
            $root = $xml->documentElement;//得到根节点

            $child = $root->getElementsByTagName($child);//得到全部子节点


            for ($i = 0; $i < $child->length; $i++)
            {

                if (($child->item($i)->nodeValue) == $old_child_value)
                {
                    $child->item($i)->nodeValue = $new_child_value;
                    $xml->save($path);
                    unset($xml);//销毁变量
                    return 1;
                    break;
                }
            }
            return 0;
        }
        catch(Exception $e)
        {
            Log::error('人为错误：在操作xml时;时间:'.time().';代码行:'.__FILE__.__LINE__.';');
            return 0;
        }
    }

    /*
     * @power写入有三级子节点的XML，有一个根源素，一个二级元素，两个三级元素。适用与一个三级元素是key 一个三级元素是value的情况
     * @param $path为文件位置;$parent为父节点;$child为子节点
     * @return {1:success;0:faild}
     * @idea 先查找有没有这个节点，再修改
     */
    public function write_2node($path, $root, $parent, $child, $child_value, $child2, $child2_value)
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml -> formatOutput = true;

        $root = $xml -> createElement($root);//新建节点
        $xml -> appendChild($root);//设置root为跟节点

        $parent = $xml -> createElement($parent);//新建父节点
        $root -> appendChild($parent);//设置index为root子节点

        $child = $xml -> createElement($child);//新建子节点1
        $parent -> appendChild($child);//把子节点给父节点

        $child2 = $xml -> createElement($child2);//新建子节点2
        $parent -> appendChild($child2);//把子节点给父节点

        $content = $xml ->createTextNode($child_value);//设置一个内容
        $child->appendChild($content);//把内容给一个子节点

        $content2 = $xml ->createTextNode($child2_value);//设置一个内容
        $child2->appendChild($content2);//把内容给一个子节点

        if( $xml -> save($path) ==1 )//保存文件
        {
            unset($xml);
            return 1;
        }
        return 0;
    }
    /*
     * @power 追加三级节点。适用与一个三级元素是key 一个三级元素是value的情况
     * @param$path:文件位置;$parent为父节点;$child其中一个子节点;$child_Value一个子节点的值;$child2第二个子节点;$child2_value第二个子节点的值
     * @return 0:失败;1:成功
     */
    public function add_2node($path, $parent, $child, $child_value, $child2, $child2_value)
    {
        $xml = new DOMDocument();
        try
        {
            $xml->load($path);//实例化
            $root = $xml->documentElement;//得到根节点

            $parent = $xml -> createElement($parent);//建立父节点
            $root -> appendChild($parent);//添加父节点

            $child = $xml -> createElement($child);//建立子节点1
            $parent -> appendChild($child);//添加子节点

            $child2 = $xml -> createElement($child2);//建立子节点2
            $parent -> appendChild($child2);//添加子节点2

            $val = $xml ->createTextNode($child_value);//设置值
            $child -> appendChild($val);//添加值

            $val2 = $xml -> createTextNode($child2_value);//设置值
            $child2 -> appendChild($val2);//添加值

            if ($xml -> save($path))
            {
                unset($xml);
                return 1;
            }
            else
            {
                unset($xml);
                return 0;
            }
        }
         catch(Exception $e)
         {
             return 0;
         }
    }
    /*
     * @power 修改两个三级元素的XML。适用与一个三级元素是key 一个三级元素是value的情况。根据key的值修改key和value
     *@param $path 文件位置;$parent:xml父节点;$child:三级节点1;$old_child_value需要查找的元素值;$new_child_key:更换的值;$child2:三级节点2;$new_child_value2更换的value
     * @todo现在的功能值是根据第一个三级节点的值去修改元素
     */
    public function edit_2node($path, $parent, $child, $old_child_value, $new_child_value, $child2, $new_child_value2)
    {
        $xml = new DOMDocument();
        try
        {
            $xml->load($path);

            $main = $xml->getElementsByTagName($parent);

            foreach ($main as $main)
            {
                $list = $main->getElementsByTagName($child);//key的值
                $list1 = $main->getElementsByTagName($child2);//value的值

                //下面的code是把代码放入
                for ($i = 0; $i < $list->length; $i++)
                {
                   if ($list->item($i)->firstChild->nodeValue == $old_child_value)
                   {
                       $list->item($i)->firstChild->nodeValue = $new_child_value;
                       $list1->item($i)->firstChild->nodeValue = $new_child_value2;
                       $xml -> save($path);
                       unset($xml);//销毁变量
                       return 1;
                       break;
                   }
                }
                return 0;
            }
        }
        catch(Exception $e)
        {
            Log::error('{人为错误：在操纵xml时；时间'.time().';代码行:'.__FILE__.__LINE__.';');
            return 0;
        }
    }
    /*
     * @power读取两个三级元素的XML。适用与一个三级元素是key 一个三级元素是value的情况
     * @param $path 文件位置;$parent为包含信息的父节点;$child为子节点;$child为第二个子节点。这个地方不需要有总的节点
     * @return array二级数组
     */
    public function read_2node($path, $parent, $child, $child2)
    {
        $xml = new DOMDocument();
        $xml->load($path);
        $main = $xml->getElementsByTagName($parent);
        $ay = [];

        foreach( $main as $main)
        {
            $list = $main->getElementsByTagName($child);
            $list1 = $main->getElementsByTagName($child2);

//            print_r($list->length);
//            print_r($list->item(0)->firstChild->nodeValue);
//            var_dump(count($list));die();

//            foreach ( $list as $val )
//            {
//                array_push($ay, array($val->firstChild->nodeValue));
//            }
//            foreach ( $list1 as $val )
//            {
//                array_push($ay1, array($val->firstChild->nodeValue));
//            }

            //下面的code是把代码放入
            for ($i = 0; $i < $list->length; $i++)
            {
                $ay[$i] = array($list->item($i)->firstChild->nodeValue, $list1->item($i)->firstChild->nodeValue);
            }
        }
        unset($xml);//销毁变量
        return $ay;
    }
    /*
     * @power 根据key找到一组三级节点。适用于有两个三级节点的xml
     * @param$path:文件位置;$parent:父节点;$child:key的三级节点;$child_value:key的值
     */
    public function found_2node($path, $parent, $child, $child_value, $child2)
    {
        $xml = new DOMDocument();
        try
        {
             $xml -> load($path);

            $main = $xml->getElementsByTagName($parent);
            $ay = [];

            foreach ($main as $main)
            {
                $list = $main->getElementsByTagName($child);//key的值
                $list1 = $main->getElementsByTagName($child2);

                //下面的code是把代码放入
                for ($i = 0; $i < $list->length; $i++)
                {
                    if ($list->item($i)->firstChild->nodeValue == $child_value)
                    {
                        $ay = array($child_value, $list1->item($i)->firstChild->nodeValue);
                        unset($xml);//销毁变量
//                        var_dump($ay);die();
                        return $ay;
                        break;
                    }
                }
                return 0;
            }
        }
        catch(Exception $e)
        {
            Log::error('{人为错误：在操纵xml时；时间'.time().';代码行:'.__FILE__.__LINE__.';');
            return 0;
        }

    }

}