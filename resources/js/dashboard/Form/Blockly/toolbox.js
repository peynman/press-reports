import RegisterEloquent from './eloquent'
import RegisterConditions from './conditions'
import RegisterKeyValue from './key_value'

export function RegisterBlocks(Blockly) {
  RegisterEloquent(Blockly);
  RegisterConditions(Blockly);
  RegisterKeyValue(Blockly);
}

export default `
<xml xmlns="https://developers.google.com/blockly/xml" id="toolbox" style="display: none">
  <category name="Generators"></category>
  <category name="Database">
    <block type="eloquent">
      <field name="PAGINATE">TRUE</field>
      <field name="AJAX">TRUE</field>
    </block>
    <block type="condition_where_null">
      <field name="COND">is</field>
    </block>
    <block type="condition_where_between"></block>
    <block type="condition">
      <field name="COND">AND</field>
    </block>
    <block type="condition_where">
      <field name="NAME">==</field>
    </block>
    <block type="condition_where_has"></block>
    <block type="condition_where_in"></block>
  </category>
  <category name="Text">
    <block type="text">
      <field name="TEXT"></field>
    </block>
    <block type="text_charAt">
      <mutation at="true"></mutation>
      <field name="WHERE">FROM_START</field>
      <value name="VALUE">
        <block type="variables_get">
          <field name="VAR" id="^4m=V\`]!|E.(QLyAfkCZ">text</field>
        </block>
      </value>
    </block>
    <block type="text_join">
      <mutation items="2"></mutation>
    </block>
    <block type="text_getSubstring">
      <mutation at1="true" at2="true"></mutation>
      <field name="WHERE1">FROM_START</field>
      <field name="WHERE2">FROM_START</field>
      <value name="STRING">
        <block type="variables_get">
          <field name="VAR" id="^4m=V\`]!|E.(QLyAfkCZ">text</field>
        </block>
      </value>
    </block>
    <block type="text_indexOf">
      <field name="END">FIRST</field>
      <value name="VALUE">
        <block type="variables_get">
          <field name="VAR" id="^4m=V\`]!|E.(QLyAfkCZ">text</field>
        </block>
      </value>
      <value name="FIND">
        <shadow type="text">
          <field name="TEXT">abc</field>
        </shadow>
      </value>
    </block>
    <block type="text_length">
      <value name="VALUE">
        <shadow type="text">
          <field name="TEXT">abc</field>
        </shadow>
      </value>
    </block>
    <block type="text_changeCase">
      <field name="CASE">UPPERCASE</field>
      <value name="TEXT">
        <shadow type="text">
          <field name="TEXT">abc</field>
        </shadow>
      </value>
    </block>
    <block type="text_isEmpty"></block>
    <shadow type="text">
      <field name="TEXT"></field>
    </shadow>
    <block type="text_trim">
      <field name="MODE">BOTH</field>
      <value name="TEXT">
        <shadow type="text">
          <field name="TEXT">abc</field>
        </shadow>
      </value>
    </block>
    <block type="text_getSubstring">
      <mutation at1="true" at2="true"></mutation>
      <field name="WHERE1">FROM_START</field>
      <field name="WHERE2">FROM_START</field>
      <value name="STRING">
        <block type="variables_get">
          <field name="VAR" id="^4m=V\`]!|E.(QLyAfkCZ">text</field>
        </block>
      </value>
    </block>
  </category>
  <category name="Lists">
    <block type="lists_create_with">
      <mutation items="0"></mutation>
    </block>
    <block type="list_dictionary_item"></block>
    <block type="lists_create_with">
      <mutation items="3"></mutation>
    </block>
    <block type="lists_split">
      <mutation mode="SPLIT"></mutation>
      <field name="MODE">SPLIT</field>
      <value name="DELIM">
        <shadow type="text">
          <field name="TEXT">,</field>
        </shadow>
      </value>
    </block>
    <block type="lists_repeat">
      <value name="NUM">
        <shadow type="math_number">
          <field name="NUM">5</field>
        </shadow>
      </value>
    </block>
    <block type="lists_getSublist">
      <mutation at1="true" at2="true"></mutation>
      <field name="WHERE1">FROM_START</field>
      <field name="WHERE2">FROM_START</field>
      <value name="LIST">
        <block type="variables_get">
          <field name="VAR" id="bDcF8=8LqZ:rv.v@vIYg">list</field>
        </block>
      </value>
    </block>
    <block type="lists_setIndex">
      <mutation at="true"></mutation>
      <field name="MODE">SET</field>
      <field name="WHERE">FROM_START</field>
      <value name="LIST">
        <block type="variables_get">
          <field name="VAR" id="bDcF8=8LqZ:rv.v@vIYg">list</field>
        </block>
      </value>
    </block>
    <block type="lists_length"></block>
    <block type="lists_indexOf">
      <field name="END">FIRST</field>
      <value name="VALUE">
        <block type="variables_get">
          <field name="VAR" id="bDcF8=8LqZ:rv.v@vIYg">list</field>
        </block>
      </value>
    </block>
    <block type="lists_isEmpty"></block>
    <block type="lists_sort">
      <field name="TYPE">NUMERIC</field>
      <field name="DIRECTION">1</field>
    </block>
  </category>
  <category name="Logic and Loops">
    <block type="controls_forEach">
      <field name="VAR" id="%3dg/L8u));)O2Kd9?Il">j</field>
    </block>
    <block type="controls_if"></block>
    <block type="logic_compare">
      <field name="OP">EQ</field>
    </block>
    <block type="logic_boolean">
      <field name="BOOL">TRUE</field>
    </block>
    <block type="logic_operation">
      <field name="OP">AND</field>
    </block>
    <block type="logic_negate"></block>
    <block type="logic_null"></block>
    <block type="logic_ternary"></block>
  </category>
  <category name="Variables" colour="#a55b80" custom="VARIABLE"></category>
</xml>`;
