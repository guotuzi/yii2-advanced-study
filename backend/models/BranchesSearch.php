<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Branches;

/**
 * BranchesSearch represents the model behind the search form about `backend\models\Branches`.
 */

// yii2 将从数据库中调用数据出来，专门建立了search 文件，
//从而 使搜索的 规则和 写入的规则进行分开，好厉害的框架
class BranchesSearch extends Branches
{
    /**
     * @inheritdoc
     */

    //搜索的验证规则
    public function rules()
    {
        return [
            [['branch_id'], 'integer'],   //规定，这些字段必须是 int 型的

            // 字段 email 在create、update场景下会验证，在除这个场景以外的其他任意场景都是safe（即不做验证）的
            [['branch_name', 'branch_address', 'companies_company_id', 'branch_created_date', 'branch_status'], 'safe'],
        ];   // 这些字段是安全的，不用验证
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    // 搜索数据，检测，过滤，导出最后可用的数据
    public function search($params)
    {
        $query = Branches::find();    //查找到全部的数据

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);    // load 函数检测数据表是否存在

        if (!$this->validate()) {  // rules 规则进行验证
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // 此处以下都是验证通过的数据
        // 连接查询，默认left join
        $query->joinWith('companiesCompany');

        // grid filtering conditions
        // 这里面的字段直接从数据表中调取，展示出来，
        $query->andFilterWhere([
            'branch_id' => $this->branch_id,
            //'companies_company_id' => $this->companies_company_id, （为了添加搜索框，放到后面去了）
            'branch_created_date' => $this->branch_created_date,
        ]);

        // 以下部分是在搜索字段上添加搜索框，搜索框中的值的，like 查询；
        $query->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'branch_address', $this->branch_address])
            ->andFilterWhere(['like', 'branch_status', $this->branch_status])

            //连接查询的条件，  like查询, 查询company表的company_name字段，  连接查询的连接字段：companies_company_id
            ->andFilterWhere(['like', 'companies.company_name', $this->companies_company_id]);

        return $dataProvider;
    }
}
