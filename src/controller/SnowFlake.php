<?php
namespace src\controller;

/*
  一、雪花算法
原理图：
1bit-不用：0，这个是无意义的。因为二进制里第一个 bit 为如果是 1，那么都是负数，但是我们生成的 id 都是正数，所以第一个 bit 统一都是 0。

41bit-时间戳：表示的是时间戳，41 bit 可以表示的数字多达 2^41 - 1，也就是可以标识 2 ^ 41 - 1 个毫秒值，换算成年就是表示 69 年的时间。

10bit-工作机器id：表示服务器id，代表的是这个服务最多可以部署在 2^10 台机器上，也就是 1024 台机器。

12bit-序列号：表示1毫秒内产生的不同 id，12 bit 可以代表的最大正整数是 2 ^ 12 - 1 = 4096，也就是说可以用这个 12 bit 代表的数字来区分同一个毫秒内的 4096 个不同的 id。
*/

class snowFlake
{
    const EPOCH = 1668223750000; // 开始时间，微秒
    const max12bit = 4095; // 12位随机序列号范围
    const max41bit = 1000000000000; //41位初始时间戳
    static $machineId = 1; // 10位机器码

    public static function generateId($mid = 1)
    {
        if ($mid) {
            self::$machineId = $mid;
        }

//        当前时间戳微秒调整
        $time = floor(microtime(true) * 1000);

//        当前时间与开始时间之间的时间差
        $time = $time - self::EPOCH;

        //41位二进制码
        $base = decbin(self::max41bit + $time);
//        第一位补0
        $base = str_pad($base, 42, "0", STR_PAD_LEFT);

//        10位机器码
        $machineId = str_pad(decbin(self::$machineId), 10, "0", STR_PAD_LEFT);

//        12位随机序号
        $random = str_pad(decbin(mt_rand(0, self::max12bit)), 12, "0", STR_PAD_LEFT);

//        64位
        $base = $base . $machineId . $random;
        return bindec($base);
    }

}

//echo snowFlake::generateId();

/*
   分布式系统为什么不用自增id，要用雪花算法生成id?
   为什么数据库id自增和uuid不适合分布式id id自增：当数据量庞大时，在数据库分库分表后，数据库自增id不能满足唯一id来标识数据；
   因为每个表都按自己节奏自增，会造成id冲突，无法满足需求。
   分库分表：分表就是把一个表的数据放到多个表中，将一个库的数据拆分到多个库中。 uuid:UUID长且无序。
   主键应越短越好，无序会造成每一次UUID数据的插入都会对主键的b+树进行很大的修改
   在时间上， uuid由于占用的内存更大，所以查询、排序速度会相对较慢；
   在存储过程中，自增长id由于主键的值是顺序的，所以InnoDB把每一条记录都存储在上一条记录的后面。
   当达到页的最大填充因子时（innodb默认的最大填充因子为页大小的15/16，留出部分空间用于以后修改），
   下一条记录就会写入新的页面中。一旦数据按照这种方式加载，主键页就会被顺序的记录填满。
   而对于uuid，由于后面的值不一定比前面的值大，所以InnoDB并不能总是把新行插入的索引的后面，
   而是需要为新行寻找合适的位置（通常在已有行之间），并分配空间。
*/