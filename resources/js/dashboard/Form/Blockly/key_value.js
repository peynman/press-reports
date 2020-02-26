export default function (Blockly) {
    Blockly.Blocks['list_dictionary_item'] = {
        init: function () {
            this.appendValueInput("KEY")
                .setCheck(null)
                .setAlign(Blockly.ALIGN_RIGHT)
                .appendField("set");
            this.appendValueInput("VAL")
                .setCheck(null)
                .setAlign(Blockly.ALIGN_RIGHT)
                .appendField("to");
            this.setInputsInline(false);
            this.setOutput(true, null);
            this.setColour(260);
            this.setTooltip("");
            this.setHelpUrl("");
        }
    };
}
