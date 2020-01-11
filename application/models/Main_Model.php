<?php
/**
 * 
 */
class Main_Model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	public function login ($user, $pwrd) {
		$query = $this->db->select('admin_password as pw, admin_username as un')->from('admin')->where('admin_username',$user)->get();
		$q = $query->row();
		if ($q) {
			if (password_verify($pwrd, $q->pw)) {
				$this->session->admincbros = $q->un;
				return 'success';
			} else {
				return 'err2';
			}
			
		} else {
			return 'err1';
		}
	}

	public function edit_admin ($act = null, $user = null, $pwrd = null, $data) {
		$query = $this->db->select('admin_password as pw, admin_id as id')->from('admin')->where('admin_username',$user)->get();
		$q = $query->row();
		if (password_verify($pwrd, $q->pw)) {

			switch ($act) {
				case 'username':
					if (!empty($data['username'])) {
						$this->db->where(array('admin_id' => $q->id))->update('admin', array('admin_username' => $data['username']));
						$this->session->admincbros = $data['username'];
						return ['msg' => 'Username updated successfully!', 'stat' => 'success'];
					} else {
						return ['msg' => 'Username is empty!', 'stat' => 'danger'];
					}
					break;
				
				case 'password':
					$hash = password_verify($data['npwrd'], PASSWORD_BCRYPT);
					if ($data['npwrd'] === $data['cpwrd']) {
						$this->db->where(array('admin_id' => $q->id))->update('admin', array('admin_password' => $hash));
						return ['msg' => 'Password updated successfully!', 'stat' => 'success'];
					} else {
						return ['msg' => 'Password did not matched!', 'stat' => 'danger'];
					}
					break;
			}

		} else {
			return ['msg' => 'Incorrect Password!', 'stat' => 'danger'];
		}
	}

	public function get_employees ($act = 'list', $start = 0, $stat = 'TRUE', $search = '', $group = 0, $sort = 1, $order = 'asc') {
		$sort_arr = [ 1 => 'emply_fname', 2 => 'emply_lname', 3 => 'emply_id'];

		if ($sort == 2) {
			$name = "CONCAT(emply_lname, ', ', emply_fname, ' ', emply_mname)";
		} else {
			$name = "CONCAT(emply_fname, ' ', emply_mname, ' ', emply_lname)";
		}
		

		$query = $this->db->select($name." as name, emply_tbl.emply_id as eID, emply_tbl.emply_address as address, group_tbl.group_name as gname")->from('emply_tbl')
					->join('group_tbl','group_tbl.group_id = emply_tbl.emply_group')
					->where(array('emply_status' => $stat));

		if ($group >= 1) {
			$query = $this->db->where(array('group_tbl.group_id' => $group));
		}

		if (!empty($search)) {
			$query = $this->db->like('emply_id', $search)
							->or_like("CONCAT(emply_lname, ', ', emply_fname, ' ', emply_mname)", $search)
							->or_like("CONCAT(emply_fname, ' ', emply_mname, ' ', emply_lname)", $search)
							->or_like("CONCAT(emply_fname, ' ', emply_lname)", $search);
		}

		if ($act == 'list') {
			$query = $this->db->order_by($sort_arr[$sort], $order)->limit(10, $start)->get();
			return $query->result_array();
		} else {
			$query = $this->db->get();
			return $query->num_rows();
		}
	}

	public function get_nextprev_employees ($id, $group = 0, $sort = 1, $order = 'asc', $stat = 'TRUE') {
		$sort_arr = [ 1 => 'emply_fname', 2 => 'emply_lname', 3 => 'emply_id'];

		if ($group >= 1) {
			$where = 'emply_status = "'.$stat.'" AND emply_group = '.$group;
		} else {
			$where = "emply_status = '$stat'";
		}

		$this->db->query("SET @num = 0");
		$rows = $this->db->query("SELECT 
							(@num := @num + 1) AS num, emply_id as id, CONCAT(emply_fname, ' ', emply_lname) as name
							FROM emply_tbl WHERE $where ORDER BY $sort_arr[$sort] $order")->result_array();
		
		$num = 0;
		$data = [];
		foreach ($rows as $r) {
			if ($r['id'] == $id) {
				$num = $r['num'];
			}
		}

		foreach ($rows as $s) {
			if ($s['num'] == ($num-1)) {
				$data['prev'] = $s;
			}
			if ($s['num'] == ($num+1)) {
				$data['next'] = $s;
			}
		}

		return $data;

	}

	public function get_groups () {
		$query = $this->db->select("COUNT(emply_id) as count, group_name as gName, group_id as gID, DATE_FORMAT(date_start, '%b. %d, %Y') as ds, 
							DATE_FORMAT(date_end, '%b. %d, %Y') as de, date_start, date_end")
							->from('group_tbl')
							->join('emply_tbl','emply_tbl.emply_group = group_tbl.group_id AND emply_tbl.emply_status = "TRUE"', 'LEFT')
							->group_by('emply_tbl.emply_group')
							->order_by('group_name')
							->get();

		return $query->result_array();
	}

	public function add_group ($data) {
		$this->db->insert('group_tbl',$data);
	}

	public function get_employee ($id) {
		$query = $this->db->select("*,CONCAT(emply_fname, ' ', emply_mname, ' ', emply_lname) as name, IFNULL(DATE_FORMAT(bday, '%M %d, %Y'), 'None') as nbday, IFNULL(TIMESTAMPDIFF(YEAR, bday, CURDATE()),0) AS age, DATE_FORMAT(date_start, '%b. %d, %Y') as ds, DATE_FORMAT(date_end, '%b. %d, %Y') as de")
					->from('emply_tbl')
					->join('group_tbl','group_tbl.group_id = emply_tbl.emply_group')
					->where(array('emply_id' => $id))
					->get();
		return $query->row();
	}

	public function add_employee ($data) {
		$query = $this->db->select('emply_id')->from('emply_tbl')->order_by('emply_id','desc')->limit(1)->get();
		$q = $query->row();
		$id = str_replace(substr($q->emply_id, 0, 2), date('y'), $q->emply_id);

		$data['emply_id'] = $id+1;
		$data['emply_status'] = 'TRUE';

		$this->db->insert('emply_tbl',$data);
		return $data['emply_id'];
	}

	public function restore_employee ($id, $stat = 'TRUE') {
		$this->db->where(array('emply_id' => $id))->update('emply_tbl', array('emply_status' => $stat));
	}

	public function get_records ($id, $s = 0, $ds = null, $de = null) {
		if (is_null($ds) && is_null($de)) {
			$arr = array('emply' => $id);
		} else {
			$arr = array('emply' => $id, 'date_start >=' => $ds, 'date_end <=' => $de);
		}
		
		$query = $this->db->select("*, 
							DATE_FORMAT(date_start, '%b. %d, %Y') as ds, 
							DATE_FORMAT(date_end, '%b. %d, %Y') as de, 
							(rate * wd) as wp, 
							(otrate * ot) as otp, 
							(shoprate * shopdays) as sp,
							((rate * wd) + (otrate * ot) + (shoprate * shopdays) + bonus + incentives) as gross,
							(tax + sss + ph + pagibig + groceries + loans + savings + advance) as deducts")
							->from('payslip_tbl')
							->where($arr)
							->order_by('id','desc')
							->limit(10, $s)
							->get();

		$query1 = $this->db->get_where('payslip_tbl', $arr);
		$total = ['wp' => 0, 'otp' => 0, 'sp' => 0, 'bonus' => 0, 'incent' => 0, 'tax' => 0, 'sss' => 0, 'ph' => 0, 'pi' => 0, 'grc' => 0, 'loans' => 0, 'sav' => 0, 'adv' => 0, 'deduct' => 0];

		foreach ($query1->result_array() as $q) {
			$total['wp'] += ($q['rate'] * $q['wd']);
			$total['otp'] += ($q['otrate'] * $q['ot']);
			$total['sp'] += ($q['shoprate'] * $q['shopdays']);
			$total['bonus'] += $q['bonus'];
			$total['incent'] += $q['incentives'];
			$total['tax'] += $q['tax'];
			$total['sss'] += $q['sss'];
			$total['ph'] += $q['ph'];
			$total['pi'] += $q['pagibig'];
			$total['grc'] += $q['groceries'];
			$total['loans'] += $q['loans'];
			$total['sav'] += $q['savings'];
			$total['adv'] += $q['advance'];
			$total['deduct'] += ($q['tax'] + $q['sss'] + $q['ph'] + $q['pagibig'] + $q['groceries'] + $q['loans'] + $q['savings'] + $q['advance']);
		}

		$data['comp'] = $total;
		$data['rows'] = $query1->num_rows();
		$data['data'] = $query->result_array();
		return $data;
	}

	public function get_advances ($id, $s = 0, $ds = null, $de = null) {
		if (is_null($ds) && is_null($de)) {
			$arr = array('emply_id' => $id);
		} else {
			$arr = array('emply_id' => $id, 'dates >=' => $ds, 'dates <=' => $de);
		}
		
		$query = $this->db->select("*, DATE_FORMAT(dates, '%b. %d, %Y') as dt")->from('advance_tbl')
							->where($arr)
							->order_by('advance_id','desc')
							->limit(10, $s)
							->get();

		$query1 = $this->db->get_where('advance_tbl', $arr);
						
		$total = 0;

		foreach ($query1->result_array() as $q) {
			$total += $q['amount'];
		}
		
		$data['comp'] = $total;
		$data['rows'] = $query1->num_rows();
		$data['data'] = $query->result_array();
		return $data;
	}

	public function add_advances ($emply, $gID, $date, $amount) {
		$query1 = $this->db->get_where('group_tbl', array('group_id' => $gID));
		$q1 = $query1->row();

		$d0 = strtotime($date);
		$d1 = strtotime($q1->date_start);

		if ($d1 <= $d0) {
			$this->db->insert('advance_tbl', array('emply_id' => $emply, 'amount' => $amount, 'dates' => $date));
			return ['msg' => 'Data added successfully!', 'stat' => 'success'];
		} else {
			return ['msg' => 'Data cannot be added, because the date is behind the given due date!', 'stat' => 'danger'];
		}
	}

	public function remove_advances ($id, $gID) {
		$query0 = $this->db->get_where('advance_tbl', array('advance_id' => $id));
		$q0 = $query0->row();

		$query1 = $this->db->get_where('group_tbl', array('group_id' => $gID));
		$q1 = $query1->row();

		$d0 = strtotime($q0->dates);
		$d1 = strtotime($q1->date_start);

		if ($d0 >= $d1) {
			$this->db->where(array('advance_id' => $id))->delete('advance_tbl');
			return ['msg' => 'Data deleted successfully!', 'stat' => 'success'];
		} else {
			return ['msg' => 'Data cannot be deleted, because its already recorded!', 'stat' => 'danger'];
		}
		
	}

	public function get_savings ($id, $s = 0, $ds = null, $de = null) {
		if (is_null($ds) && is_null($de)) {
			$arr = array('emply' => $id);
		} else {
			$arr = array('emply' => $id, 'dates >=' => $ds, 'dates <=' => $de);
		}
		
		$query = $this->db->select("*, DATE_FORMAT(dates, '%b. %d, %Y') as dt")->from('savings_tbl')
							->where($arr)
							->order_by('savings_id','desc')
							->limit(10, $s)
							->get();

		$query1 = $this->db->get_where('savings_tbl', $arr);

		$total = 0;

		foreach ($query1->result_array() as $q) {
			$total += $q['amount'];
		}

		$data['comp'] = $total;
		$data['rows'] = $query1->num_rows();
		$data['data'] = $query->result_array();
		return $data;
	}

	public function add_savings ($emply, $gID, $date, $amount) {
		$query1 = $this->db->get_where('group_tbl', array('group_id' => $gID));
		$q1 = $query1->row();

		$d0 = strtotime($date);
		$d1 = strtotime($q1->date_start);

		if ($d1 <= $d0) {
			$this->db->insert('savings_tbl', array('emply' => $emply, 'amount' => $amount, 'dates' => $date));
			return ['msg' => 'Data added successfully!', 'stat' => 'success'];
		} else {
			return ['msg' => 'Data cannot be added, because the date is behind the given due date!', 'stat' => 'danger'];
		}
	}

	public function remove_savings ($id, $gID) {
		$query0 = $this->db->get_where('savings_tbl', array('savings_id' => $id));
		$q0 = $query0->row();

		$query1 = $this->db->get_where('group_tbl', array('group_id' => $gID));
		$q1 = $query1->row();

		$d0 = strtotime($q0->dates);
		$d1 = strtotime($q1->date_start);

		if ($d0 >= $d1) {
			$this->db->where(array('savings_id' => $id))->delete('savings_tbl');
			return ['msg' => 'Data deleted successfully!', 'stat' => 'success'];
		} else {
			return ['msg' => 'Data cannot be deleted, because its already recorded!', 'stat' => 'danger'];
		}
		
	}

	public function get_last_records ($id, $s, $e) {

		$query0 = $this->db->select("
							IFNULL(emply_tbl.rate,0) as rate, IFNULL(wd,0) as wd, 
							IFNULL(emply_tbl.otrate,0) as otrate, IFNULL(ot,0) as ot, 
							IFNULL(emply_tbl.srate,0) as srate, IFNULL(shopdays,0) as sd,
							IFNULL(bonus,0) as bonus, IFNULL(incentives,0) as incent,

							IFNULL(tax,0) as tax, IFNULL(sss,0) as sss,
							IFNULL(ph,0) as ph, IFNULL(pagibig,0) as pagibig,
							IFNULL(groceries,0) as grc, IFNULL(loans,0) as loans,

							IFNULL((emply_tbl.rate * wd),0) as wp, 
							IFNULL((emply_tbl.otrate * ot),0) as otp, 
							IFNULL((emply_tbl.srate * shopdays),0) as sp,

							IFNULL(((emply_tbl.rate * wd) + (emply_tbl.otrate * ot) + (emply_tbl.srate * shopdays) + bonus + incentives),0) as gross,

							IFNULL(DATE_FORMAT(payslip_tbl.dates, '%b. %d, %Y'), 'Unknown') as date_mod
							")
							->from('emply_tbl')
							->join('payslip_tbl', "payslip_tbl.emply = emply_tbl.emply_id AND payslip_tbl.date_start = '$s' AND payslip_tbl.date_end = '$e'",'LEFT')
							->where(array('emply_tbl.emply_id' => $id))
							->get();

		$query1 = $this->db->select("IFNULL(SUM(savings_tbl.amount),0) as savings")
							->from('emply_tbl')
							->join('savings_tbl', "savings_tbl.emply = emply_tbl.emply_id AND savings_tbl.dates >= '$s' AND savings_tbl.dates <= '$e'", 'LEFT')
							->where(array('emply_tbl.emply_id' => $id))
							->get();

		$query2 = $this->db->select("IFNULL(SUM(advance_tbl.amount),0) as adv")
							->from('emply_tbl')
							->join('advance_tbl', "advance_tbl.emply_id = emply_tbl.emply_id AND advance_tbl.dates >= '$s' AND advance_tbl.dates <= '$e'", 'LEFT')
							->where(array('emply_tbl.emply_id' => $id))
							->get();

		$q0 = $query0->row();
		$q1 = $query1->row();
		$q2 = $query2->row();
		$d = array_sum(array($q0->tax,$q0->sss,$q0->ph,$q0->pagibig,$q0->grc,$q0->loans,$q1->savings,$q2->adv));

		return [
				'rate' => $q0->rate, 'wd' => $q0->wd, 'wp' => $q0->wp,
				'otrate' => $q0->otrate, 'ot' => $q0->ot, 'otp' => $q0->otp,
				'srate' => $q0->srate, 'sd' => $q0->sd, 'sp' => $q0->sp,
				'bonus' => $q0->bonus, 'incent' => $q0->incent, 'gross' => $q0->gross,
				'date_mod' => $q0->date_mod,

				'tax' => $q0->tax, 'sss' => $q0->sss, 'ph' => $q0->ph, 'pagibig' => $q0->pagibig, 'grc' => $q0->grc, 'loans' => $q0->loans,

				'savings' => $q1->savings, 'adv' => $q2->adv, 'deducts' => $d,
				];
	}

	public function update_payslip ($id, $s, $e, $d) {
		$var1 = [ 'rate' => $d['rate'], 'otrate' => $d['otrate'], 'wd' => $d['wd'], 'shoprate' => $d['srate'], 'shopdays' => $d['sd'], 'ot' => $d['ot'], 'bonus' => $d['bonus'], 'incentives' => $d['incent'], 'tax' => $d['tax'], 'sss' => $d['sss'], 'ph' => $d['ph'], 'pagibig' => $d['pagibig'], 'groceries' => $d['grc'], 'loans' => $d['loans'], 'savings' => $d['savings'], 'advance' => $d['adv'], 'dates' => date('Y-m-d')];

		$var2 = [ 'rate' => $d['rate'], 'otrate' => $d['otrate'], 'srate' => $d['srate'] ];

		$query = $this->db->get_where('payslip_tbl', array('emply' => $id, 'date_start' => $s, 'date_end' => $e))->row();

		if (!$query) {
			$var1['emply'] = $id;
			$var1['date_start'] = $s;
			$var1['date_end'] = $e;
			$this->db->insert('payslip_tbl',$var1);
		} else {
			$this->db->where(array('id' => $query->id))->update('payslip_tbl', $var1);
		}

		$this->db->where(array('emply_id' => $id))->update('emply_tbl', $var2);
	}

	public function update_group ($id, $data) {
		$this->db->where(array('group_id' => $id))->update('group_tbl', $data);
	}

	public function delete_group ($id) {
		$query = $this->db->get_where('emply_tbl', array('emply_group' => $id));
		if ($query->num_rows() <= 0) {
			$this->db->where(array('group_id' => $id))->delete('group_tbl');
			return ['msg' => 'Group has been deleted successfully!', 'stat' => 'success'];
		} else {
			return ['msg' => 'Group cannot be deleted, because it has 1 or more employees!', 'stat' => 'danger'];
		}
		
	}

	public function update_employee ($id, $data) {
		$this->db->where(array('emply_id' => $id))->update('emply_tbl', $data);
	}

	public function get_payslips ($id, $act = 'employee', $sort = 1, $order = 'asc', $start = 0) {
		$sort_arr = [ 1 => 'emply_tbl.emply_fname', 2 => 'emply_tbl.emply_lname', 3 => 'emply_tbl.emply_id'];

		$query = $this->db->select("*,
							DATE_FORMAT(group_tbl.date_start, '%b. %d, %Y') as ds, 
							DATE_FORMAT(group_tbl.date_end, '%b. %d, %Y') as de, 

							(payslip_tbl.rate * payslip_tbl.wd) as wp, 
							(payslip_tbl.otrate * payslip_tbl.ot) as otp, 
							(payslip_tbl.shoprate * payslip_tbl.shopdays) as sp,

							((payslip_tbl.rate * payslip_tbl.wd) + (payslip_tbl.otrate * payslip_tbl.ot) + (payslip_tbl.shoprate * payslip_tbl.shopdays) + payslip_tbl.bonus + payslip_tbl.incentives) as gross,

							(payslip_tbl.tax + payslip_tbl.sss + payslip_tbl.ph + payslip_tbl.pagibig + payslip_tbl.groceries + payslip_tbl.loans + payslip_tbl.savings + payslip_tbl.advance) as deducts")
							->from('emply_tbl')
							->join("group_tbl","group_tbl.group_id = emply_tbl.emply_group")
							->join("payslip_tbl", "payslip_tbl.emply = emply_tbl.emply_id AND payslip_tbl.date_start = group_tbl.date_start AND payslip_tbl.date_end = group_tbl.date_end",'LEFT');
		if ($act == 'employee') {
			$query = $this->db->where(array('emply_tbl.emply_id' => $id))->get();
		} else {
			$query = $this->db->where(array('emply_tbl.emply_status' => 'TRUE'));

			if ($id >= 1) {
				$query = $this->db->where(array('emply_tbl.emply_status' => 'TRUE', 'emply_tbl.emply_group' => $id));
			}

			$query = $this->db->order_by($sort_arr[$sort], $order)->limit(10, $start)->get();
		}

		return $query->result_array();
	}

	public function payout_summary ($year = null) {
		if (is_null($year)) {
			$year = date('Y');
		}

		$query = $this->db->select("SUM(((payslip_tbl.rate * payslip_tbl.wd) + (payslip_tbl.otrate * payslip_tbl.ot) + (payslip_tbl.shoprate * payslip_tbl.shopdays) + payslip_tbl.bonus + payslip_tbl.incentives)) as gross,

							SUM((payslip_tbl.tax + payslip_tbl.sss + payslip_tbl.ph + payslip_tbl.pagibig + payslip_tbl.groceries + payslip_tbl.loans + payslip_tbl.savings + payslip_tbl.advance)) as deducts,

							MONTH(date_start) as date

							")
						->from('payslip_tbl')
						->group_by("CONCAT(MONTH(date_start),'-',YEAR(date_start))")
						->where(array('YEAR(date_start)' => $year))
						->get();

		$res = [];
		$max = [];

		for ($i=1; $i <= 12; $i++) { 
			$m = date('M', strtotime($year.'-'.$i.'-01'));
			$res[$m] = 0;

			foreach ($query->result_array() as $q) {
				if ($q['date'] == $i) {
					$res[$m] = round(($q['gross'] - $q['deducts']));
				}
			}
		}

		foreach ($res as $r) {
			$max[] = ceil($r);
		}

		$data['data'] = $res;
		$data['max'] = max($max);

		return $data;
	}

	public function total_payouts () {
		$query = $this->db->select("SUM(((payslip_tbl.rate * payslip_tbl.wd) + (payslip_tbl.otrate * payslip_tbl.ot) + (payslip_tbl.shoprate * payslip_tbl.shopdays) + payslip_tbl.bonus + payslip_tbl.incentives)) as gross,

							SUM((payslip_tbl.tax + payslip_tbl.sss + payslip_tbl.ph + payslip_tbl.pagibig + payslip_tbl.groceries + payslip_tbl.loans + payslip_tbl.savings + payslip_tbl.advance)) as deducts,
							")
						->from('payslip_tbl')
						->get();
		$q = $query->row();

		return number_format($q->gross - $q->deducts);
	}

	public function payout_list_summary ($group = null, $date = null) {
		// $where = ['emply_tbl.emply_status' => 'TRUE'];

		if ($group >= 1 && $group != 0) {
			$where['group_tbl.group_id'] = $group;
		}

		if (!is_null($date) && !empty($date)) {
			$date = $date.'-01';

			$where['YEAR(payslip_tbl.date_start)'] = date('Y', strtotime($date));
			$where['MONTH(payslip_tbl.date_start)'] = date('m', strtotime($date));
		}

		$query = $this->db->select("((payslip_tbl.rate * payslip_tbl.wd) + (payslip_tbl.otrate * payslip_tbl.ot) + (payslip_tbl.shoprate * payslip_tbl.shopdays) + payslip_tbl.bonus + payslip_tbl.incentives) as gross,

							(payslip_tbl.tax + payslip_tbl.sss + payslip_tbl.ph + payslip_tbl.pagibig + payslip_tbl.groceries + payslip_tbl.loans + payslip_tbl.savings + payslip_tbl.advance) as deducts,

							IFNULL(CONCAT(emply_lname, ', ', emply_fname, ' ', emply_mname), 'Unknown') as name,
							group_tbl.group_name as group,
							IFNULL(emply_tbl.designation, 'Unknown') as desig,
							payslip_tbl.wd as wd,
							payslip_tbl.ot as ot,
							payslip_tbl.shopdays as sd
							")
						->from('payslip_tbl')
						->join('emply_tbl', 'emply_tbl.emply_id = payslip_tbl.emply', 'LEFT')
						->join('group_tbl', 'group_tbl.group_id = emply_tbl.emply_group', 'LEFT')
						->order_by('emply_lname')
						->where($where)
						->get();

		return $query->result_array();
	}
}