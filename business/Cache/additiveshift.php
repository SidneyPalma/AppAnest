<?php

namespace AppAnest\Cache;

use AppAnest\Model\additiveshift as Model;

class additiveshift extends \Smart\Data\Cache {

    public function selectCode(array $data) {
        $additiveid = $data['additiveid'];
        $contractorsubunitid = $data['contractorsubunitid'];

        $proxy = $this->getStore()->getProxy();

        $sql = "
            select
                a.id,
                getEnum('shift',t.shift) as shiftdescription,
                getEnum('dutytype',t.dutytype) as dutytypedescription,
                t.shift,
                t.dutytype,
                t.hours,
                t.validityof,
                t.validityto,
                :additiveid as additiveid,
                :contractorsubunitid as contractorsubunitid,
                t.id as shifttypeid,
                a.amountsun,
                a.amountmon,
                a.amounttue,
                a.amountwed,
                a.amountthu,
                a.amountfri,
                a.amountsat,
                case coalesce(a.id,0) when 0 then 0 else 1 end as isactive
            from
                shifttype t
                left join additiveshift a on ( a.shifttypeid = t.id and a.additiveid = :additiveid and a.contractorsubunitid = :contractorsubunitid )
            order by t.id";

        try {
            $pdo = $proxy->prepare($sql);

            $pdo->bindValue(":additiveid", $additiveid, \PDO::PARAM_INT);
            $pdo->bindValue(":contractorsubunitid", $contractorsubunitid, \PDO::PARAM_INT);

            $pdo->execute();
            $rows = $pdo->fetchAll();

            self::_setRows($rows);

        } catch ( \PDOException $e ) {
            self::_setSuccess(false);
            self::_setText($e->getMessage());
        }

        return self::getResultToJson();
    }

    public function selectTree(array $data) {
        $showfilter = $data['showfilter'];
        $contractid = $data['contractid'];
        $additiveid = $data['additiveid'];
        $proxy = $this->getStore()->getProxy();

        $sql = "
            select
                c.contractnumber as id,
                null as parentid,
                concat(substring(c.contractnumber, 1,3),'/',substring(c.contractnumber, 4,4),'-',a.additivenumber) as text,
                'icon-doc-text' as glyph,
                (
                    select
                        coalesce(sum(
                            coalesce(af.amountsun,0) + coalesce(af.amountmon,0) +
                            coalesce(af.amounttue,0) + coalesce(af.amountwed,0) +
                            coalesce(af.amountthu,0) + coalesce(af.amountfri,0) + coalesce(af.amountsat,0)
                        ),0) as total
                    from
                        additiveshift af,
                        contractorsubunit csu
                    where af.additiveid = a.id
                      and af.contractorsubunitid = csu.id
                      and ((af.amountsun != 0)or(af.amountmon != 0)or(af.amounttue !=0)or(af.amountwed != 0)or(af.amountthu != 0)or(af.amountfri != 0)or(af.amountsat != 0))
                ) as released,
                0 as leaf,
                a.id as additiveid
            from
                contract c
                inner join additive a on ( a.contractid = c.id )
                inner join person p on ( p.id = c.contractorid )
            where c.id = :contractid
              and a.id = :additiveid

            union all

            select
                p.id,
                c.contractnumber as parentid,
                p.shortname as text,
                'icon-certificate-outline' as glyph,
                (
                    select
                        coalesce(sum(
                            coalesce(af.amountsun,0) + coalesce(af.amountmon,0) +
                            coalesce(af.amounttue,0) + coalesce(af.amountwed,0) +
                            coalesce(af.amountthu,0) + coalesce(af.amountfri,0) + coalesce(af.amountsat,0)
                        ),0) as total
                    from
                        additiveshift af,
                        contractorsubunit csu
                    where af.additiveid = a.id
                      and af.contractorsubunitid = csu.id
                      and csu.contractorunitid = p.id
                      and ((af.amountsun != 0)or(af.amountmon != 0)or(af.amounttue !=0)or(af.amountwed != 0)or(af.amountthu != 0)or(af.amountfri != 0)or(af.amountsat != 0))
                ) as released,
                1 as leaf,
                a.id as additiveid
            from
                contract c
                inner join additive a on ( a.contractid = c.id )
                inner join person p on ( p.parentid = c.contractorid )
            where c.id = :contractid
              and a.id = :additiveid

            order by 3, 2, 1";

        try {
            $pdo = $proxy->prepare($sql);

            $pdo->bindValue(":contractid", $contractid, \PDO::PARAM_INT);
            $pdo->bindValue(":additiveid", $additiveid, \PDO::PARAM_INT);

            $pdo->execute();
            $rows = $pdo->fetchAll();

            if($showfilter != '1') {
                $data = array();

                foreach ($rows as $record => $fields) {
                    $leaf = intval($fields['leaf']);
                    $released = intval($fields['released']);

                    if ($showfilter == '2') {
                        if (($leaf == 0) || ($leaf == 1 && $released != 0)) {
                            $data[] = $fields;
                        }
                    }

                    if ($showfilter == '3') {
                        if (($leaf == 0) || ($leaf == 1 && $released == 0)) {
                            $data[] = $fields;
                        }
                    }

                }

                $rows = $data;
            }

            $root = self::buildTree($rows);

        } catch ( \PDOException $e ) {
            self::_setSuccess(false);
            self::_setText($e->getMessage());
            return self::getResultToJson();
        }

        return self::arrayToJson($root[0]);
    }

}