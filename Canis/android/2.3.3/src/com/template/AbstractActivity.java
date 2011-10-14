package com.template;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

public abstract class AbstractActivity extends Activity {
	@Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (!Utility.isConnected(getApplicationContext())) {
        	Toast.makeText(getApplicationContext(), "Network Connection is required", 10000).show();
        	return;
        }

        Log.d("Activity", getClass().getName() + "was called");
		onCreate();
	}

	protected abstract void onCreate();

}
