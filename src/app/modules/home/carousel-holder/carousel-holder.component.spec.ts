import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CarouselHolderComponent } from './carousel-holder.component';

describe('CarouselHolderComponent', () => {
  let component: CarouselHolderComponent;
  let fixture: ComponentFixture<CarouselHolderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [CarouselHolderComponent]
    });
    fixture = TestBed.createComponent(CarouselHolderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
