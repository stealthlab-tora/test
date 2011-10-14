package com.template;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;

import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;

import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.net.Uri;
import android.provider.MediaStore;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Toast;

public class PhotoActivity extends AbstractActivity {

	private Bitmap largeImage = null;
	private Bitmap thumbnailImage = null;

	private final String SDCARD_PATH = "/sdcard/";
	
	@Override
	public void onCreate() {
		
		setContentView(R.layout.photo);

		Button takePhotoBtn = (Button) this.findViewById(R.id.takePhotoButton);
		takePhotoBtn.setOnClickListener(new OnClickListener() {
			public void onClick(View arg0) {
				takePhotoButtonOnClick();
			}
		});
		Button chooseBtn = (Button) this.findViewById(R.id.chooseButton);
		chooseBtn.setOnClickListener(new OnClickListener() {
			public void onClick(View arg0) {
				chooseButtonOnClick();
			}
		});
		Button sendImageBtn = (Button) this.findViewById(R.id.buttonSendImageToServer);
		sendImageBtn.setOnClickListener(new OnClickListener() {
			public void onClick(View arg0) {
				sendImageButtonOnClick();
			}
		});
	}

	protected void chooseButtonOnClick() {
		Intent intent = new Intent();
		intent.setType("image/*");
		intent.setAction(Intent.ACTION_GET_CONTENT);
		startActivityForResult(intent, 10);
	}

	protected void takePhotoButtonOnClick() {
		Intent intent = new Intent();
		intent.setAction("android.media.action.IMAGE_CAPTURE");
		startActivityForResult(intent, 11);
	}

	public String getPath(Uri uri) {
		String[] projection = { MediaStore.Images.Media.DATA };
		Cursor cursor = managedQuery(uri, projection, null, null, null);
		int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
		cursor.moveToFirst();
		return cursor.getString(column_index);
	}

	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		if (resultCode == RESULT_OK) {
			Log.d("Code", "Request:" + String.valueOf(requestCode) + " ,Result:" + String.valueOf(resultCode));

			try {
				Bitmap image = null;
				
				// Choose Image
				if (requestCode == 10) {
					Uri selectedImageUri = data.getData();
					Log.d("Image Path:", getPath(selectedImageUri));
					InputStream in = getContentResolver().openInputStream(data.getData());
					image = BitmapFactory.decodeStream(in);

				// Take a Photo
				} else if (requestCode == 11) {
					image = (Bitmap) data.getExtras().get("data");
				}

				resizeImage(image);

			} catch (Exception e) {
				Log.e("Image Error", "Error Occured");
				e.printStackTrace();
				//Toast.makeText(getApplicationContext(), "Image activity failed", 10000).show();
				return;
			}
		}
	}

	private void resizeImage(Bitmap image) throws Exception{

		int srcWidth = image.getWidth(); // original width
		int srcHeight = image.getHeight(); // original height

		// Large(320*320)
		ImageView imgLargeView = (ImageView) findViewById(R.id.imageLargeView);

		float screenLargeWidth = 320;
		float screenLargeHeight = 320;

		float widthLargeScale = screenLargeWidth / srcWidth;
		float heighLargetScale = screenLargeHeight / srcHeight;

		Matrix largeMatrix = new Matrix();
		largeMatrix.postScale(widthLargeScale, heighLargetScale);

		largeImage = Bitmap.createBitmap(image, 0, 0, srcWidth,
				srcHeight, largeMatrix, true);
		imgLargeView.setImageBitmap(largeImage);

		// Thumbnail(64*64)
		ImageView imgThumbnailView = (ImageView) findViewById(R.id.imageThumbnailView);

		float screenThumbnailWidth = 64;
		float screenThumbnailHeight = 64;

		float widthThmubnailScale = screenThumbnailWidth / srcWidth;
		float heighThmubnailScale = screenThumbnailHeight / srcHeight;

		Matrix thumbnailMatrix = new Matrix();
		thumbnailMatrix.postScale(widthThmubnailScale,
				heighThmubnailScale);

		thumbnailImage = Bitmap.createBitmap(image, 0, 0, srcWidth,
				srcHeight, thumbnailMatrix, true);
		imgThumbnailView.setImageBitmap(thumbnailImage);

	}

	protected void sendImageButtonOnClick() {

		try {
			// Large
			ByteArrayOutputStream largeJpeg = new ByteArrayOutputStream();
			largeImage.compress(Bitmap.CompressFormat.JPEG, 100, largeJpeg);

			// Thumbnail
			ByteArrayOutputStream thumbnailJpeg = new ByteArrayOutputStream();
			thumbnailImage.compress(Bitmap.CompressFormat.JPEG, 100, thumbnailJpeg);

			// Need to store temporary file. Because of InputStreamBody is NonRepeatable
			String largeTempFile = createTemporaryFile(largeJpeg);
			String thumbnailTempFile = createTemporaryFile(thumbnailJpeg);
			
			HttpClient httpClient = new DefaultHttpClient();

			HttpPost request = new HttpPost("http://phpapi-az.dotcloud.com/api/ItemSaveController.php");

			MultipartEntity entity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);

			FileBody largeFileBody = new FileBody(new File(SDCARD_PATH + largeTempFile));
			entity.addPart("image", largeFileBody);

			FileBody thumbnailFileBody = new FileBody(new File(SDCARD_PATH + thumbnailTempFile));
			entity.addPart("imageThumbnail", thumbnailFileBody);

			request.setEntity(entity);

			// TODO Connection timeout 
			ResponseHandler<String> responseHandler = new BasicResponseHandler();
			String response = httpClient.execute(request, responseHandler);

			if (!response.equals("true")) {
				Toast.makeText(getApplicationContext(), "Image upload failed", 1000).show();
				return;
			}
			
			deteteTemporaryFile(SDCARD_PATH + largeTempFile);
			deteteTemporaryFile(SDCARD_PATH + thumbnailTempFile);

			Toast.makeText(getApplicationContext(), "Image upload completed", 1000).show();

		} catch(Exception e) {
			e.printStackTrace();
		}
	}

	public String createTemporaryFile(ByteArrayOutputStream jpegData) {
		int randomBefore = (int) (Math.random() * 100);
		long currentTimeMillis = System.currentTimeMillis();
		int randomAfter = (int) (Math.random() * 100);
		String fileName = randomBefore + String.valueOf(currentTimeMillis) + randomAfter;
		FileOutputStream fos = null;
		try {
			fos = new FileOutputStream(SDCARD_PATH + fileName);
			fos.write(jpegData.toByteArray());
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			try {
				fos.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		Log.d("filename", fileName);
		return fileName;
	}
	
	public void deteteTemporaryFile(String filePath){
		File file = new File(filePath);
		file.delete();
	}
}
