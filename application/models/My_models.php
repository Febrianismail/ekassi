<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_models extends CI_Model
{
    public function get_data($select = [], $table = [], $where = [], $join = [])
    {
        if ($select != null) {
            # code...
            $this->db->select($select);
        }
        $this->db->from($table);
        if ($where != null) {
            # code...
            foreach ($where as $wherevalaue => $wheredata) {
                # code...
                $this->db->where($wherevalaue, $wheredata);
            }
        }
        if ($join != null) {
            # code...
            foreach ($join as $joinvalue => $joindata) {
                # code...
                $this->db->join($joinvalue, $joindata);
            }
        }
        return $this->db->get();
    }


    private function _get_datatables_query($select, $table, $join, $column_order, $column_search, $order)
    {
        if ($select != null) {
            # code...
            $this->db->select($select);
        } else {
            # code...
            $this->db->select();
        }
        $this->db->from($table);
        if ($join != null) {
            # code...
            foreach ($join as $joinkey => $key) {
                # code...
                $this->db->join($joinkey, $key);
            }
        }

        $i = 0;
        if ($column_search != null) {
            # code...
            foreach ($column_search as $items) {
                # code...
                if ($_POST['search']['value']) {
                    # code...
                    if ($i === 0) {
                        # code...
                        $this->db->group_start();
                        $this->db->like($items, $_POST['search']['value']);
                    } else {
                        # code...
                        $this->db->or_like($items, $_POST['search']['value']);
                    }
                    if (count($column_search) - 1 == $i) {
                        # code...
                        $this->db->group_end();
                    }
                    $i++;
                }

                if (isset($_POST['order'])) {
                    $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
                } elseif (isset($order)) {
                    # code...
                    $this->db->order_by(key($order), $order[key($order)]);
                }
            }
        }
    }

    function get_datatables($select = null, $table = null, $join = null, $column_order = null, $column_search = null, $order = null)
    {
        $this->_get_datatables_query($select, $table, $join, $column_order, $column_search, $order);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query;
        }
    }

    function count_filtered($select = null, $table = null, $join = null, $column_order, $column_search = null, $order = null)
    {
        $this->_get_datatables_query($select, $table, $join, $column_order, $column_search, $order);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table = null)
    {
        $this->db->from($table);
        return $this->db->count_all_results();
    }
}
