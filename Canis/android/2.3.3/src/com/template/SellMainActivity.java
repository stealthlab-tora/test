package com.template;

import android.content.Intent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

public class SellMainActivity extends AbstractActivity {

	@Override
    public void onCreate() {
        setContentView(R.layout.sell);
        Button photoBtn = (Button) this.findViewById(R.id.photoButton);  
        photoBtn.setOnClickListener(new OnClickListener(){  
			public void onClick(View arg0) {
                photoButtonOnClick();  
			}  
        });          
	}

	protected void photoButtonOnClick() {
        Intent intent = new Intent(this, com.template.PhotoActivity.class);
        startActivityForResult(intent, 10); // 10 is Request Code
	}
}
