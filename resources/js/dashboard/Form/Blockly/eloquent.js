export default function(Blockly) {
    Blockly.Blocks['eloquent'] = {
        init: function () {
            this.appendValueInput("CRUD")
                .setCheck("String")
                .appendField("Query for");
            this.appendValueInput("ATTACH")
                .setCheck(["String", "Array"])
                .appendField("and attach ");
            this.appendValueInput("CONDITIONS")
                .setCheck("condition")
                .setAlign(Blockly.ALIGN_RIGHT)
                .appendField("with conditions");
            this.appendDummyInput()
                .appendField(new Blockly.FieldCheckbox("TRUE"), "PAGINATE")
                .appendField("paginate");
            this.appendDummyInput()
                .appendField(new Blockly.FieldCheckbox("TRUE"), "AJAX")
                .appendField("AJAX");
            this.setInputsInline(false);
            this.setOutput(true, null);
            this.setColour(270);
            this.setTooltip("Query database for objects");
            this.setHelpUrl("");
        }
    };
}