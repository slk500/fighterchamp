fos.Router.setData({
    "base_url": "",
    "routes": {
        "club_show": {
            "tokens": [["variable", "\/", "[^\/]++", "id"], ["text", "\/kluby"]],
            "defaults": [],
            "requirements": [],
            "hosttokens": [],
            "methods": [],
            "schemes": []
        },
        "setNullOnImage": {
            "tokens": [["text", "\/setnullonimage"]],
            "defaults": [],
            "requirements": [],
            "hosttokens": [],
            "methods": [],
            "schemes": []
        },
        "tqm_i_will_help": {
            "tokens": [["variable", "\/", "[^\/]++", "id"], ["text", "\/tqm\/task"]],
            "defaults": [],
            "requirements": [],
            "hosttokens": [],
            "methods": [],
            "schemes": []
        },
        "user_show": {
            "tokens": [["variable", "\/", "\\d+", "id"], ["text", "\/ludzie"]],
            "defaults": [],
            "requirements": {"id": "\\d+"},
            "hosttokens": [],
            "methods": ["GET"],
            "schemes": []
        },
        "view_user_register_form": {
            "tokens": [["variable", "\/", "[^\/]++", "type"], ["text", "\/ludzie\/register-form"]],
            "defaults": [],
            "requirements": [],
            "hosttokens": [],
            "methods": ["GET"],
            "schemes": []
        },
        "view_user_update_form": {
            "tokens": [["variable", "\/", "[^\/]++", "type"], ["text", "\/ludzie\/update-form"]],
            "defaults": [],
            "requirements": [],
            "hosttokens": [],
            "methods": ["GET"],
            "schemes": []
        }
    },
    "prefix": "",
    "host": "localhost",
    "scheme": "http"
});