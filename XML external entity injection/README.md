## Người thực hiện: Trần Ngọc Nam
## Thời gian thực hiện: 24/5/2022

# Mục lục:
- [Mục lục:](#mục-lục)
  - [XML external entity injection:](#xml-external-entity-injection)
  - [Các lỗ hổng XXE phát sinh như thế nào?](#các-lỗ-hổng-xxe-phát-sinh-như-thế-nào)
  - [Các loại tấn công XXE là gì?](#các-loại-tấn-công-xxe-là-gì)
    - [Khai thác XXE để truy xuất tệp:](#khai-thác-xxe-để-truy-xuất-tệp)
    - [Khai thác XXE để thực hiện các cuộc tấn công SSRF:](#khai-thác-xxe-để-thực-hiện-các-cuộc-tấn-công-ssrf)
    - [Tấn công XXE thông qua tải lên tệp:](#tấn-công-xxe-thông-qua-tải-lên-tệp)
    - [Tấn công XXE thông qua kiểu nội dung đã sửa đổi:](#tấn-công-xxe-thông-qua-kiểu-nội-dung-đã-sửa-đổi)
  - [Cách ngăn chặn lỗ hổng XXE:](#cách-ngăn-chặn-lỗ-hổng-xxe)

## XML external entity injection:
- XML external entity injection (còn được gọi là XXE) là một lỗ hổng bảo mật web cho phép kẻ tấn công can thiệp vào việc xử lý dữ liệu XML của ứng dụng.
- XEE thường cho phép kẻ tấn công xem các tệp trên hệ thống tệp máy chủ ứng dụng và tương tác với bất kỳ hệ thống back-end hoặc bên ngoài nào mà chính ứng dụng có thể truy cập.
- Trong một số tình huống, kẻ tấn công có thể leo thang một cuộc tấn công XXE để thỏa hiệp máy chủ cơ bản hoặc cơ sở hạ tầng back-end khác, bằng cách tận dụng lỗ hổng XXE để thực hiện các cuộc tấn công giả mạo yêu cầu phía máy chủ (SSRF).

## Các lỗ hổng XXE phát sinh như thế nào?
- Một số ứng dụng sử dụng định dạng XML để truyền dữ liệu giữa trình duyệt và máy chủ. Các ứng dụng này thường sử dụng thư viện tiêu chuẩn hoặc API nền tảng để xử lý dữ liệu XML trên máy chủ. Các lỗ hổng XXE phát sinh vì đặc điểm kỹ thuật XML chứa các tính năng nguy hiểm tiềm tàng khác nhau và các phân tích tiêu chuẩn hỗ trợ các tính năng này ngay cả khi chúng thường không được ứng dụng sử dụng.
- Các thực thể bên ngoài XML là một loại thực thể XML tùy chỉnh có các giá trị được xác định được tải từ bên ngoài DTD(document type definitions) mà chúng được khai báo. Các thực thể bên ngoài đặc biệt thú vị từ góc độ bảo mật vì chúng cho phép một thực thể được xác định dựa trên nội dung của đường dẫn tệp hoặc URL.

## Các loại tấn công XXE là gì?
- Có nhiều loại tấn công XXE khác nhau:
  - Khai thác XXE để truy xuất tệp: thực thể bên ngoài được xác định có chứa nội dung của tệp và trả về trong phản hồi của ứng dụng.
  - Khai thác XXE để thực hiện các cuộc tấn công SSRF: thực thể bên ngoài được xác định dựa trên URL đến hệ thống back-end.
  - Khai thác XXE mù trích xuất dữ liệu ngoài băng tần: nơi dữ liệu nhạy cảm được truyền từ máy chủ ứng dụng đến một hệ thống mà kẻ tấn công kiểm soát.
  - Khai thác XXE mù để truy xuất dữ liệu thông qua các thông báo lỗi: nơi kẻ tấn công có thể kích hoạt thông báo lỗi phân tích có chứa dữ liệu nhạy cảm.

### Khai thác XXE để truy xuất tệp:
- Để thực hiện một cuộc tấn công tiêm XXE lấy một tệp tùy ý từ hệ thống tệp của máy chủ, bạn cần sửa đổi XML đã gửi theo hai cách:
  - Chỉnh sửa một phần tử xác định một thực thể bên ngoài có chứa đường dẫn đến tệp. DOCTYPE
  - Sửa giá trị dữ liệu trong XML được trả về trong phản hồi của ứng dụng, để sử dụng thực thể bên ngoài được xác định.
- Ví dụ: giả sử một ứng dụng mua sắm kiểm tra mức tồn kho của sản phẩm bằng cách gửi XML sau đây cho máy chủ
  ```php
  <?xml version="1.0" encoding="UTF-8"?>
  <stockCheck><productId>381</productId></stockCheck>
  ```
- Ứng dụng không thực hiện các biện pháp phòng thủ cụ thể chống lại các cuộc tấn công XXE, vì vậy bạn có thể khai thác lỗ hổng XXE để truy xuất tệp bằng cách gửi tải trọng XXE sau đây <code>/etc/passwd</code>.
  ```php
  <?xml version="1.0" encoding="UTF-8"?>
  <!DOCTYPE foo [ <!ENTITY xxe SYSTEM "file:///etc/passwd"> ]>
  <stockCheck><productId>&xxe;</productId></stockCheck>
  ```
- Tải trọng XXE này xác định một thực thể bên ngoài có giá trị là nội dung của tệp và sử dụng thực thể trong giá trị. Điều này làm cho phản hồi của ứng dụng bao gồm nội dung của tệp: <code>&xxe;/etc/passwdproductId</code>.
  ```php
  Invalid product ID: root:x:0:0:root:/root:/bin/bash
  daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
  bin:x:2:2:bin:/bin:/usr/sbin/nologin
  ...
  ```

### Khai thác XXE để thực hiện các cuộc tấn công SSRF:
- Để khai thác lỗ hổng XXE để thực hiện cuộc tấn công SSRF, bạn cần xác định một thực thể XML bên ngoài bằng cách sử dụng URL mà bạn muốn nhắm mục tiêu và sử dụng thực thể được xác định trong giá trị dữ liệu.
- Nếu bạn có thể sử dụng thực thể được xác định trong một giá trị dữ liệu được trả về trong phản hồi của ứng dụng, thì bạn sẽ có thể xem phản hồi từ URL trong phản hồi của ứng dụng và do đó có được tương tác hai chiều với hệ thống back-end.
- Nếu không, thì bạn sẽ chỉ có thể thực hiện các cuộc tấn công SSRF mù (vẫn có thể gây ra hậu quả nghiêm trọng).

### Tấn công XXE thông qua tải lên tệp:
- Một số ứng dụng cho phép người dùng tải lên các tệp sau đó được xử lý phía máy chủ. Một số định dạng tệp phổ biến sử dụng XML hoặc chứa các thành phần con XML. Ví dụ về các định dạng dựa trên XML là các định dạng tài liệu văn phòng như DOCX và định dạng hình ảnh như SVG.
- Ví dụ: một ứng dụng có thể cho phép người dùng tải lên hình ảnh và xử lý hoặc xác thực chúng trên máy chủ sau khi chúng được tải lên. Ngay cả khi ứng dụng dự kiến sẽ nhận được định dạng như PNG hoặc JPEG, thư viện xử lý hình ảnh đang được sử dụng có thể hỗ trợ hình ảnh SVG. Vì định dạng SVG sử dụng XML, kẻ tấn công có thể gửi hình ảnh SVG độc hại và do đó tiếp cận bề mặt tấn công ẩn cho các lỗ hổng XXE.

### Tấn công XXE thông qua kiểu nội dung đã sửa đổi:
- Hầu hết các yêu cầu POST sử dụng kiểu nội dung mặc định được tạo bởi các biểu mẫu HTML. Một số trang web mong đợi nhận được yêu cầu ở định dạng này nhưng sẽ dung nạp các loại nội dung khác, bao gồm XML <code>application/x-www-form-urlencoded</code>.
- Ví dụ:
  ```phhp
  POST /action HTTP/1.0
  Content-Type: application/x-www-form-urlencoded
  Content-Length: 7
  
  foo=bar
  ```
- Sau đó, bạn có thể gửi yêu cầu sau đây, với cùng một kết quả
  ```php
  POST /action HTTP/1.0
  Content-Type: text/xml
  Content-Length: 52
  
  <?xml version="1.0" encoding="UTF-8"?><foo>bar</foo>
  ```
- Nếu ứng dụng chấp nhận các yêu cầu chứa XML trong nội dung thư và phân tích cú pháp nội dung dưới dạng XML, thì bạn có thể tiếp cận bề mặt tấn công XXE ẩn chỉ đơn giản bằng cách định dạng lại các yêu cầu để sử dụng định dạng XML.

## Cách ngăn chặn lỗ hổng XXE:
- Hầu như tất cả các lỗ hổng XXE phát sinh vì thư viện phân tích XML của ứng dụng hỗ trợ các tính năng XML nguy hiểm tiềm tàng mà ứng dụng không cần hoặc có ý định sử dụng. Cách dễ nhất và hiệu quả nhất để ngăn chặn các cuộc tấn công XXE là vô hiệu hóa các tính năng đó.