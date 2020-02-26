export default function (Blockly) {
    Blockly.Blocks['condition'] = {
        init: function () {
            this.appendValueInput("CON1")
                .setCheck("condition")
                .appendField("when");
            this.appendValueInput("CON2")
                .setCheck("condition")
                .appendField(new Blockly.FieldDropdown([
                    ["and", "AND"],
                    ["or", "OR"]
                ]), "COND");
            this.setInputsInline(false);
            this.setOutput(true, "condition");
            this.setColour(285);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };

    Blockly.Blocks['condition_where'] = {
        init: function () {
            this.appendValueInput("COL")
                .setCheck("String")
                .appendField("where");
            this.appendDummyInput()
                .appendField(new Blockly.FieldDropdown([
                    ["==", "=="],
                    [">", ">"],
                    ["<", "<"],
                    [">=", ">="],
                    ["<=", "<="],
                    ["!=", "!="]
                ]), "NAME");
            this.appendValueInput("VAL")
                .setCheck("String");
            this.setInputsInline(true);
            this.setOutput(true, "condition");
            this.setColour(300);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };

    Blockly.Blocks['condition_where_in'] = {
        init: function () {
            this.appendValueInput("COL")
                .setCheck("String")
                .appendField("where");
            this.appendValueInput("VALS")
                .setCheck("Array")
                .appendField("in");
            this.setInputsInline(true);
            this.setOutput(true, "condition");
            this.setColour(300);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };

    Blockly.Blocks['condition_where_has'] = {
        init: function () {
            this.appendValueInput("RELATION")
                .setCheck("String")
                .appendField("where has");
            this.appendValueInput("NAME")
                .setCheck("condition")
                .setAlign(Blockly.ALIGN_RIGHT)
                .appendField("with conditions");
            this.setInputsInline(false);
            this.setOutput(true, "condition");
            this.setColour(300);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };

    Blockly.Blocks['condition_where_between'] = {
        init: function () {
            this.appendValueInput("COL")
                .setCheck("String")
                .appendField("where");
            this.appendValueInput("BETWEEN_0")
                .setCheck(null)
                .appendField("is between");
            this.appendValueInput("NAME")
                .setCheck(null)
                .appendField("and");
            this.setInputsInline(true);
            this.setOutput(true, "condition");
            this.setColour(300);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };

    Blockly.Blocks['condition_where_null'] = {
        init: function () {
            this.appendValueInput("COL")
                .setCheck("String")
                .appendField("where");
            this.appendDummyInput()
                .appendField(new Blockly.FieldDropdown([
                    ["is", "is"],
                    ["is not", "is_not"]
                ]), "COND");
            this.appendDummyInput()
                .appendField("null");
            this.setInputsInline(true);
            this.setOutput(true, "condition");
            this.setColour(300);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };
}
