const Node = {
    content: "block+",
    group: "block",
    defining: true,
    parseDOM: [{tag: "blockquote"}],
    toDOM() { return ["blockquote", 0] }
    // toDOM: node => ["span", {
    //     "trans-key": node.attrs.key,
    // }, "welcoome"],
    // parseDOM: [{
    //     tag: "span[trans-key]",
    //     getAttrs: dom => {
    //         return {
    //             key: dom.getAttribute("trans-key"),
    //         }
    //     }
    // }]
}

export default Node;