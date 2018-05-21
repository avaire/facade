package com.avairebot.facade.routes;

import com.avairebot.facade.Facade;
import com.avairebot.facade.contracts.spark.SparkRoute;
import org.json.JSONObject;
import spark.Request;
import spark.Response;

public class GetRoot extends SparkRoute {

    public GetRoot(Facade facade) {
        super(facade);
    }

    @Override
    public Object handle(Request request, Response response) throws Exception {
        JSONObject json = new JSONObject();
        json.put("text", "Hello, World!");

        return json;
    }
}
